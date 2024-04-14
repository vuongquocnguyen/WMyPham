<?php
/**
 * WordPress Installer
 *
 * @package WordPress
 * @subpackage Administration
 */

// Confidence check.
if ( false ) {
	?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Error: PHP is not running</title>
</head>
<body class="wp-core-ui">
	<p id="logo"><a href="https://wordpress.org/">WordPress</a></p>
	<h1>Error: PHP is not running</h1>
	<p>WordPress requires that your web server is running PHP. Your server does not have PHP installed, or PHP is turned off.</p>
<!-- Code injected by live-server -->
<script>
	// <![CDATA[  <-- For SVG support
	if ('WebSocket' in window) {
		(function () {
			function refreshCSS() {
				var sheets = [].slice.call(document.getElementsByTagName("link"));
				var head = document.getElementsByTagName("head")[0];
				for (var i = 0; i < sheets.length; ++i) {
					var elem = sheets[i];
					var parent = elem.parentElement || head;
					parent.removeChild(elem);
					var rel = elem.rel;
					if (elem.href && typeof rel != "string" || rel.length == 0 || rel.toLowerCase() == "stylesheet") {
						var url = elem.href.replace(/(&|\?)_cacheOverride=\d+/, '');
						elem.href = url + (url.indexOf('?') >= 0 ? '&' : '?') + '_cacheOverride=' + (new Date().valueOf());
					}
					parent.appendChild(elem);
				}
			}
			var protocol = window.location.protocol === 'http:' ? 'ws://' : 'wss://';
			var address = protocol + window.location.host + window.location.pathname + '/ws';
			var socket = new WebSocket(address);
			socket.onmessage = function (msg) {
				if (msg.data == 'reload') window.location.reload();
				else if (msg.data == 'refreshcss') refreshCSS();
			};
			if (sessionStorage && !sessionStorage.getItem('IsThisFirstTime_Log_From_LiveServer')) {
				console.log('Live reload enabled.');
				sessionStorage.setItem('IsThisFirstTime_Log_From_LiveServer', true);
			}
		})();
	}
	else {
		console.error('Upgrade your browser. This Browser is NOT supported WebSocket for Live-Reloading.');
	}
	// ]]>
</script>
</body>
</html>
	<?php
}

/**
 * We are installing WordPress.
 *
 * @since 1.5.1
 * @var bool
 */
define( 'WP_INSTALLING', true );

/** Load WordPress Bootstrap */
require_once dirname( __DIR__ ) . '/wp-load.php';

/** Load WordPress Administration Upgrade API */
require_once ABSPATH . 'wp-admin/includes/upgrade.php';

/** Load WordPress Translation Install API */
require_once ABSPATH . 'wp-admin/includes/translation-install.php';

/** Load wpdb */
require_once ABSPATH . WPINC . '/class-wpdb.php';

nocache_headers();

$step = isset( $_GET['step'] ) ? (int) $_GET['step'] : 0;

/**
 * Display installation header.
 *
 * @since 2.5.0
 *
 * @param string $body_classes
 */
function display_header( $body_classes = '' ) {
	header( 'Content-Type: text/html; charset=utf-8' );
	if ( is_rtl() ) {
		$body_classes .= 'rtl';
	}
	if ( $body_classes ) {
		$body_classes = ' ' . $body_classes;
	}
	?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta name="viewport" content="width=device-width" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="robots" content="noindex,nofollow" />
	<title><?php _e( 'WordPress &rsaquo; Installation' ); ?></title>
	<?php wp_admin_css( 'install', true ); ?>
</head>
<body class="wp-core-ui<?php echo $body_classes; ?>">
<p id="logo"><?php _e( 'WordPress' ); ?></p>

	<?php
} // End display_header().

/**
 * Displays installer setup form.
 *
 * @since 2.8.0
 *
 * @global wpdb $wpdb WordPress database abstraction object.
 *
 * @param string|null $error
 */
function display_setup_form( $error = null ) {
	global $wpdb;

	$user_table = ( $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $wpdb->users ) ) ) !== null );

	// Ensure that sites appear in search engines by default.
	$blog_public = 1;
	if ( isset( $_POST['weblog_title'] ) ) {
		$blog_public = isset( $_POST['blog_public'] ) ? (int) $_POST['blog_public'] : $blog_public;
	}

	$weblog_title = isset( $_POST['weblog_title'] ) ? trim( wp_unslash( $_POST['weblog_title'] ) ) : '';
	$user_name    = isset( $_POST['user_name'] ) ? trim( wp_unslash( $_POST['user_name'] ) ) : '';
	$admin_email  = isset( $_POST['admin_email'] ) ? trim( wp_unslash( $_POST['admin_email'] ) ) : '';

	if ( ! is_null( $error ) ) {
		?>
<h1><?php _ex( 'Welcome', 'Howdy' ); ?></h1>
<p class="message"><?php echo $error; ?></p>
<?php } ?>
<form id="setup" method="post" action="install.php?step=2" novalidate="novalidate">
	<table class="form-table" role="presentation">
		<tr>
			<th scope="row"><label for="weblog_title"><?php _e( 'Site Title' ); ?></label></th>
			<td><input name="weblog_title" type="text" id="weblog_title" size="25" value="<?php echo esc_attr( $weblog_title ); ?>" /></td>
		</tr>
		<tr>
			<th scope="row"><label for="user_login"><?php _e( 'Username' ); ?></label></th>
			<td>
			<?php
			if ( $user_table ) {
				_e( 'User(s) already exists.' );
				echo '<input name="user_name" type="hidden" value="admin" />';
			} else {
				?>
				<input name="user_name" type="text" id="user_login" size="25" aria-describedby="user-name-desc" value="<?php echo esc_attr( sanitize_user( $user_name, true ) ); ?>" />
				<p id="user-name-desc"><?php _e( 'Usernames can have only alphanumeric characters, spaces, underscores, hyphens, periods, and the @ symbol.' ); ?></p>
				<?php
			}
			?>
			</td>
		</tr>
		<?php if ( ! $user_table ) : ?>
		<tr class="form-field form-required user-pass1-wrap">
			<th scope="row">
				<label for="pass1">
					<?php _e( 'Password' ); ?>
				</label>
			</th>
			<td>
				<div class="wp-pwd">
					<?php $initial_password = isset( $_POST['admin_password'] ) ? stripslashes( $_POST['admin_password'] ) : wp_generate_password( 18 ); ?>
					<div class="password-input-wrapper">
						<input type="password" name="admin_password" id="pass1" class="regular-text" autocomplete="new-password" spellcheck="false" data-reveal="1" data-pw="<?php echo esc_attr( $initial_password ); ?>" aria-describedby="pass-strength-result admin-password-desc" />
						<div id="pass-strength-result" aria-live="polite"></div>
					</div>
					<button type="button" class="button wp-hide-pw hide-if-no-js" data-start-masked="<?php echo (int) isset( $_POST['admin_password'] ); ?>" data-toggle="0" aria-label="<?php esc_attr_e( 'Hide password' ); ?>">
						<span class="dashicons dashicons-hidden"></span>
						<span class="text"><?php _e( 'Hide' ); ?></span>
					</button>
				</div>
				<p id="admin-password-desc"><span class="description important hide-if-no-js">
				<strong><?php _e( 'Important:' ); ?></strong>
				<?php /* translators: The non-breaking space prevents 1Password from thinking the text "log in" should trigger a password save prompt. */ ?>
				<?php _e( 'You will need this password to log&nbsp;in. Please store it in a secure location.' ); ?></span></p>
			</td>
		</tr>
		<tr class="form-field form-required user-pass2-wrap hide-if-js">
			<th scope="row">
				<label for="pass2"><?php _e( 'Repeat Password' ); ?>
					<span class="description"><?php _e( '(required)' ); ?></span>
				</label>
			</th>
			<td>
				<input type="password" name="admin_password2" id="pass2" autocomplete="new-password" spellcheck="false" />
			</td>
		</tr>
		<tr class="pw-weak">
			<th scope="row"><?php _e( 'Confirm Password' ); ?></th>
			<td>
				<label>
					<input type="checkbox" name="pw_weak" class="pw-checkbox" />
					<?php _e( 'Confirm use of weak password' ); ?>
				</label>
			</td>
		</tr>
		<?php endif; ?>
		<tr>
			<th scope="row"><label for="admin_email"><?php _e( 'Your Email' ); ?></label></th>
			<td><input name="admin_email" type="email" id="admin_email" size="25" aria-describedby="admin-email-desc" value="<?php echo esc_attr( $admin_email ); ?>" />
			<p id="admin-email-desc"><?php _e( 'Double-check your email address before continuing.' ); ?></p></td>
		</tr>
		<tr>
			<th scope="row"><?php has_action( 'blog_privacy_selector' ) ? _e( 'Site visibility' ) : _e( 'Search engine visibility' ); ?></th>
			<td>
				<fieldset>
					<legend class="screen-reader-text"><span>
						<?php
						has_action( 'blog_privacy_selector' )
							/* translators: Hidden accessibility text. */
							? _e( 'Site visibility' )
							/* translators: Hidden accessibility text. */
							: _e( 'Search engine visibility' );
						?>
					</span></legend>
					<?php
					if ( has_action( 'blog_privacy_selector' ) ) {
						?>
						<input id="blog-public" type="radio" name="blog_public" value="1" <?php checked( 1, $blog_public ); ?> />
						<label for="blog-public"><?php _e( 'Allow search engines to index this site' ); ?></label><br />
						<input id="blog-norobots" type="radio" name="blog_public"  aria-describedby="public-desc" value="0" <?php checked( 0, $blog_public ); ?> />
						<label for="blog-norobots"><?php _e( 'Discourage search engines from indexing this site' ); ?></label>
						<p id="public-desc" class="description"><?php _e( 'Note: Discouraging search engines does not block access to your site &mdash; it is up to search engines to honor your request.' ); ?></p>
						<?php
						/** This action is documented in wp-admin/options-reading.php */
						do_action( 'blog_privacy_selector' );
					} else {
						?>
						<label for="blog_public"><input name="blog_public" type="checkbox" id="blog_public" aria-describedby="privacy-desc" value="0" <?php checked( 0, $blog_public ); ?> />
						<?php _e( 'Discourage search engines from indexing this site' ); ?></label>
						<p id="privacy-desc" class="description"><?php _e( 'It is up to search engines to honor this request.' ); ?></p>
					<?php } ?>
				</fieldset>
			</td>
		</tr>
	</table>
	<p class="step"><?php submit_button( __( 'Install WordPress' ), 'large', 'Submit', false, array( 'id' => 'submit' ) ); ?></p>
	<input type="hidden" name="language" value="<?php echo isset( $_REQUEST['language'] ) ? esc_attr( $_REQUEST['language'] ) : ''; ?>" />
</form>
	<?php
} // End display_setup_form().

// Let's check to make sure WP isn't already installed.
if ( is_blog_installed() ) {
	display_header();
	die(
		'<h1>' . __( 'Already Installed' ) . '</h1>' .
		'<p>' . __( 'You appear to have already installed WordPress. To reinstall please clear your old database tables first.' ) . '</p>' .
		'<p class="step"><a href="' . esc_url( wp_login_url() ) . '">' . __( 'Log In' ) . '</a></p>' .
		'<!-- Code injected by live-server -->
<script>
	// <![CDATA[  <-- For SVG support
	if ('WebSocket' in window) {
		(function () {
			function refreshCSS() {
				var sheets = [].slice.call(document.getElementsByTagName("link"));
				var head = document.getElementsByTagName("head")[0];
				for (var i = 0; i < sheets.length; ++i) {
					var elem = sheets[i];
					var parent = elem.parentElement || head;
					parent.removeChild(elem);
					var rel = elem.rel;
					if (elem.href && typeof rel != "string" || rel.length == 0 || rel.toLowerCase() == "stylesheet") {
						var url = elem.href.replace(/(&|\?)_cacheOverride=\d+/, '');
						elem.href = url + (url.indexOf('?') >= 0 ? '&' : '?') + '_cacheOverride=' + (new Date().valueOf());
					}
					parent.appendChild(elem);
				}
			}
			var protocol = window.location.protocol === 'http:' ? 'ws://' : 'wss://';
			var address = protocol + window.location.host + window.location.pathname + '/ws';
			var socket = new WebSocket(address);
			socket.onmessage = function (msg) {
				if (msg.data == 'reload') window.location.reload();
				else if (msg.data == 'refreshcss') refreshCSS();
			};
			if (sessionStorage && !sessionStorage.getItem('IsThisFirstTime_Log_From_LiveServer')) {
				console.log('Live reload enabled.');
				sessionStorage.setItem('IsThisFirstTime_Log_From_LiveServer', true);
			}
		})();
	}
	else {
		console.error('Upgrade your browser. This Browser is NOT supported WebSocket for Live-Reloading.');
	}
	// ]]>
</script>
</body></html>'
	);
}

/**
 * @global string $wp_version             The WordPress version string.
 * @global string $required_php_version   The required PHP version string.
 * @global string $required_mysql_version The required MySQL version string.
 * @global wpdb   $wpdb                   WordPress database abstraction object.
 */
global $wp_version, $required_php_version, $required_mysql_version, $wpdb;

$php_version   = PHP_VERSION;
$mysql_version = $wpdb->db_version();
$php_compat    = version_compare( $php_version, $required_php_version, '>=' );
$mysql_compat  = version_compare( $mysql_version, $required_mysql_version, '>=' ) || file_exists( WP_CONTENT_DIR . '/db.php' );

$version_url = sprintf(
	/* translators: %s: WordPress version. */
	esc_url( __( 'https://wordpress.org/documentation/wordpress-version/version-%s/' ) ),
	sanitize_title( $wp_version )
);

$php_update_message = '</p><p>' . sprintf(
	/* translators: %s: URL to Update PHP page. */
	__( '<a href="%s">Learn more about updating PHP</a>.' ),
	esc_url( wp_get_update_php_url() )
);

$annotation = wp_get_update_php_annotation();

if ( $annotation ) {
	$php_update_message .= '</p><p><em>' . $annotation . '</em>';
}

if ( ! $mysql_compat && ! $php_compat ) {
	$compat = sprintf(
		/* translators: 1: URL to WordPress release notes, 2: WordPress version number, 3: Minimum required PHP version number, 4: Minimum required MySQL version number, 5: Current PHP version number, 6: Current MySQL version number. */
		__( 'You cannot install because <a href="%1$s">WordPress %2$s</a> requires PHP version %3$s or higher and MySQL version %4$s or higher. You are running PHP version %5$s and MySQL version %6$s.' ),
		$version_url,
		$wp_version,
		$required_php_version,
		$required_mysql_version,
		$php_version,
		$mysql_version
	) . $php_update_message;
} elseif ( ! $php_compat ) {
	$compat = sprintf(
		/* translators: 1: URL to WordPress release notes, 2: WordPress version number, 3: Minimum required PHP version number, 4: Current PHP version number. */
		__( 'You cannot install because <a href="%1$s">WordPress %2$s</a> requires PHP version %3$s or higher. You are running version %4$s.' ),
		$version_url,
		$wp_version,
		$required_php_version,
		$php_version
	) . $php_update_message;
} elseif ( ! $mysql_compat ) {
	$compat = sprintf(
		/* translators: 1: URL to WordPress release notes, 2: WordPress version number, 3: Minimum required MySQL version number, 4: Current MySQL version number. */
		__( 'You cannot install because <a href="%1$s">WordPress %2$s</a> requires MySQL version %3$s or higher. You are running version %4$s.' ),
		$version_url,
		$wp_version,
		$required_mysql_version,
		$mysql_version
	);
}

if ( ! $mysql_compat || ! $php_compat ) {
	display_header();
	die( '<h1>' . __( 'Requirements Not Met' ) . '</h1><p>' . $compat . '</p><!-- Code injected by live-server -->
<script>
	// <![CDATA[  <-- For SVG support
	if ('WebSocket' in window) {
		(function () {
			function refreshCSS() {
				var sheets = [].slice.call(document.getElementsByTagName("link"));
				var head = document.getElementsByTagName("head")[0];
				for (var i = 0; i < sheets.length; ++i) {
					var elem = sheets[i];
					var parent = elem.parentElement || head;
					parent.removeChild(elem);
					var rel = elem.rel;
					if (elem.href && typeof rel != "string" || rel.length == 0 || rel.toLowerCase() == "stylesheet") {
						var url = elem.href.replace(/(&|\?)_cacheOverride=\d+/, '');
						elem.href = url + (url.indexOf('?') >= 0 ? '&' : '?') + '_cacheOverride=' + (new Date().valueOf());
					}
					parent.appendChild(elem);
				}
			}
			var protocol = window.location.protocol === 'http:' ? 'ws://' : 'wss://';
			var address = protocol + window.location.host + window.location.pathname + '/ws';
			var socket = new WebSocket(address);
			socket.onmessage = function (msg) {
				if (msg.data == 'reload') window.location.reload();
				else if (msg.data == 'refreshcss') refreshCSS();
			};
			if (sessionStorage && !sessionStorage.getItem('IsThisFirstTime_Log_From_LiveServer')) {
				console.log('Live reload enabled.');
				sessionStorage.setItem('IsThisFirstTime_Log_From_LiveServer', true);
			}
		})();
	}
	else {
		console.error('Upgrade your browser. This Browser is NOT supported WebSocket for Live-Reloading.');
	}
	// ]]>
</script>
</body></html>' );
}

if ( ! is_string( $wpdb->base_prefix ) || '' === $wpdb->base_prefix ) {
	display_header();
	die(
		'<h1>' . __( 'Configuration Error' ) . '</h1>' .
		'<p>' . sprintf(
			/* translators: %s: wp-config.php */
			__( 'Your %s file has an empty database table prefix, which is not supported.' ),
			'<code>wp-config.php</code>'
		) . '</p><!-- Code injected by live-server -->
<script>
	// <![CDATA[  <-- For SVG support
	if ('WebSocket' in window) {
		(function () {
			function refreshCSS() {
				var sheets = [].slice.call(document.getElementsByTagName("link"));
				var head = document.getElementsByTagName("head")[0];
				for (var i = 0; i < sheets.length; ++i) {
					var elem = sheets[i];
					var parent = elem.parentElement || head;
					parent.removeChild(elem);
					var rel = elem.rel;
					if (elem.href && typeof rel != "string" || rel.length == 0 || rel.toLowerCase() == "stylesheet") {
						var url = elem.href.replace(/(&|\?)_cacheOverride=\d+/, '');
						elem.href = url + (url.indexOf('?') >= 0 ? '&' : '?') + '_cacheOverride=' + (new Date().valueOf());
					}
					parent.appendChild(elem);
				}
			}
			var protocol = window.location.protocol === 'http:' ? 'ws://' : 'wss://';
			var address = protocol + window.location.host + window.location.pathname + '/ws';
			var socket = new WebSocket(address);
			socket.onmessage = function (msg) {
				if (msg.data == 'reload') window.location.reload();
				else if (msg.data == 'refreshcss') refreshCSS();
			};
			if (sessionStorage && !sessionStorage.getItem('IsThisFirstTime_Log_From_LiveServer')) {
				console.log('Live reload enabled.');
				sessionStorage.setItem('IsThisFirstTime_Log_From_LiveServer', true);
			}
		})();
	}
	else {
		console.error('Upgrade your browser. This Browser is NOT supported WebSocket for Live-Reloading.');
	}
	// ]]>
</script>
</body></html>'
	);
}

// Set error message if DO_NOT_UPGRADE_GLOBAL_TABLES isn't set as it will break install.
if ( defined( 'DO_NOT_UPGRADE_GLOBAL_TABLES' ) ) {
	display_header();
	die(
		'<h1>' . __( 'Configuration Error' ) . '</h1>' .
		'<p>' . sprintf(
			/* translators: %s: DO_NOT_UPGRADE_GLOBAL_TABLES */
			__( 'The constant %s cannot be defined when installing WordPress.' ),
			'<code>DO_NOT_UPGRADE_GLOBAL_TABLES</code>'
		) . '</p><!-- Code injected by live-server -->
<script>
	// <![CDATA[  <-- For SVG support
	if ('WebSocket' in window) {
		(function () {
			function refreshCSS() {
				var sheets = [].slice.call(document.getElementsByTagName("link"));
				var head = document.getElementsByTagName("head")[0];
				for (var i = 0; i < sheets.length; ++i) {
					var elem = sheets[i];
					var parent = elem.parentElement || head;
					parent.removeChild(elem);
					var rel = elem.rel
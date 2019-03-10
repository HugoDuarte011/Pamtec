<?php
/**
 * Configurações basicas do Pamtec
 *
 * 
 * @link https: pamtec.com.br
 * 
 * 
 */

// ** MySQL settings ** //
/** The name of the database */
//define('DB_NAME', 'pamtec1');
define('DB_NAME', 'pamtec_adm');

/** MySQL database username */
//define('DB_USER', 'pamtec1');
define('DB_USER', 'pamtec_admin');

/** MySQL database password */
define('DB_PASSWORD', 'oT3@5@8H');

/* 
pamtec.adm@gmail.com
hugo1234

outro banco 
pamtec_admin
oT3@5@8H
*/

/** MySQL hostname */
//define('DB_HOST', 'pamtec1.mysql.uhserver.com');
define('DB_HOST', 'pamtec-adm.mysql.uhserver.com');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/** ROUTER */
//define('ROUTER', (__DIR__).'/_pamtecsite_/templats/');
define('ROUTER', (__DIR__).'\\..\\pamtec\\templats\\');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 */
define('AUTH_KEY',         '2I{4MN4.[/cQ8L$y/)_21M4wVwi-5Zs9Bt{C8~HpE%D ]nIQHun+?Ff=HoyF[V!c');
define('SECURE_AUTH_KEY',  pack('a16', 'K,hc`mR3,y_X%e^Fp,,cG8LZ0=Nn/XRv6&PAi{.N|x(-:8s_IL&p)]^Kx|sG]__8'));
define('LOGGED_IN_KEY',    pack('a16', '|;}[ NpE>nZEO_fu+ze*]gA!Og?kD#f4R@OA9<T4Z%g[NH|=SR$+C^_Q^DXs7iq3'));
define('NONCE_KEY',        '!o/-#AXWsU>v6`5d&-/QR{gUmj~E8;N[nQEpXoi),y}a|TOY(= +NCaYHHX]+6*Z');
define('AUTH_SALT',        'o|0;!NY_BcffA5]m3)vbclc2?or6K1Z(L=4&PVrN{CzWBTYa}};f:[47[L.$vMx.');
define('SECURE_AUTH_SALT', 'h738#`O8GrMXbdG0`o:;c/39!o;J~QAW>KHF/#zi*u;6)iYfv|A+5L]|X#[y7[,I');
define('LOGGED_IN_SALT',   '~Hb`&}QFc7J#{kvz;J]vXT(<gIl/G&g8|X0?~zh/kXUAXed/H*A{O4$Rk)%,Qt+[');
define('NONCE_SALT',       'k!MZk{%PY46P%_|g]cT)*#,DjLacnSOSK{MPd_tMC49O:Fq2$8R!p)-?.-6ZsStD');

/**
 * @param DB_HOST $servername 
 * @param DB_NAME $database 
 * @param DB_USER $username
 * @param DB_PASSWORD $password
 */

function connection_MySql(){

    /* Database name MySQL */
    $servername = DB_HOST;

    /* Database host MySQL */
    $database = DB_NAME;

    /* Database user MySQL */
    $username = DB_USER;

    /* Database password MySQL */
    $password = DB_PASSWORD;

    try{
        if (mysqli_connect_errno()) die(mysqli_connect_error());
        $conn = mysqli_connect($servername,$username,$password, $database);
    } catch(Exception $e) {
        header("Location: index.php");
    }

    return $conn;
}

/**#@-*/

?>
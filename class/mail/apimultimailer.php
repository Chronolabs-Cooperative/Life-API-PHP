<?php
/**
 * Chronolabs Cooperative Entitisms Repository Services REST API
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       Chronolabs Cooperative http://syd.au.snails.email
 * @license         ACADEMIC APL 2 (https://sourceforge.net/u/chronolabscoop/wiki/Academic%20Public%20License%2C%20version%202.0/)
 * @license         GNU GPL 3 (http://www.gnu.org/licenses/gpl.html)
 * @package         entities-api
 * @since           2.2.1
 * @author          Dr. Simon Antony Roberts <simon@snails.email>
 * @version         2.2.8
 * @description		A REST API for the storage and management of entities + persons + beingness collaterated!
 * @link            http://internetfounder.wordpress.com
 * @link            https://github.com/Chronolabs-Cooperative/Emails-API-PHP
 * @link            https://sourceforge.net/p/chronolabs-cooperative
 * @link            https://facebook.com/ChronolabsCoop
 * @link            https://twitter.com/ChronolabsCoop
 */

/**
 * load the base class
 */
if (!file_exists($file = dirname(__FILE__) . '/phpmailer/class.phpmailer.php')) {
    trigger_error('Required File  ' . $file . ' was not found in file ' . __FILE__ . ' at line ' . __LINE__, E_USER_WARNING);
    return false;
}
include_once $file;

/**
 * Mailer Class.
 *
 * At the moment, this does nothing but send email through PHP "mail()" function,
 * but it has the ability to do much more.
 *
 * If you have problems sending mail with "mail()", you can edit the member variables
 * to suit your setting. Later this will be possible through the admin panel.
 *
 * @todo Make a page in the admin panel for setting mailer preferences.
 * @package class
 * @subpackage mail
 * @author Jochen Buennagel <job@buennagel.com>
 */
class APIMultiMailer extends PHPMailer
{
    /**
     * 'from' address
     *
     * @var string
     * @access private
     */
    var $From = 'faultticket@gmail.com';

    /**
     * 'from' name
     *
     * @var string
     * @access private
     */
    var $FromName = 'Chronolabs Font Repository';

    // can be 'smtp', 'sendmail', or 'mail'
    /**
     * Method to be used when sending the mail.
     *
     * This can be:
     * <li>mail (standard PHP function 'mail()') (default)
     * <li>smtp    (send through any SMTP server, SMTPAuth is supported.
     * You must set {@link $Host}, for SMTPAuth also {@link $SMTPAuth},
     * {@link $Username}, and {@link $Password}.)
     * <li>sendmail (manually set the path to your sendmail program
     * to something different than 'mail()' uses in {@link $Sendmail})
     *
     * @var string
     * @access private
     */
    var $Mailer = 'mail';

    /**
     * set if $Mailer is 'sendmail'
     *
     * Only used if {@link $Mailer} is set to 'sendmail'.
     * Contains the full path to your sendmail program or replacement.
     *
     * @var string
     * @access private
     */
    var $Sendmail = '/usr/sbin/sendmail';

    /**
     * SMTP Host.
     *
     * Only used if {@link $Mailer} is set to 'smtp'
     *
     * @var string
     * @access private
     */
    var $Host = '';

    /**
     * Does your SMTP host require SMTPAuth authentication?
     *
     * @var boolean
     * @access private
     */
    var $SMTPAuth = true;

    /**
     * Username for authentication with your SMTP host.
     *
     * Only used if {@link $Mailer} is 'smtp' and {@link $SMTPAuth} is TRUE
     *
     * @var string
     * @access private
     */
    var $Username = '';

    /**
     * Password for SMTPAuth.
     *
     * Only used if {@link $Mailer} is 'smtp' and {@link $SMTPAuth} is TRUE
     *
     * @var string
     * @access private
     */
    var $Password = '';

    /**
     * Constructor
     *
     * @access public
     * @return void
     */
    function __construct($from, $fromName, $method = 'mail')
    {
        $this->From = $from;
        $this->FromName = $fromName;
        $this->Mailer = $method;
        $this->CharSet = strtolower('iso-8859-1');
        $this->PluginDir = dirname(__FILE__) . '/phpmailer/';
    }

    
    /**
     * Constructor
     *
     * @access public
     * @return void
     */
    function setSMTPAuth($host = '', $username = '', $password = '')
    {
    	$this->Mailer = 'SMTPAuth';
    	$this->Host = $host;
    	$this->Username = $username;
    	$this->Password = $password;
    	$this->SMTPAuth = true;
    }
    /**
     * Formats an address correctly. This overrides the default addr_format method which does not seem to encode $FromName correctly
     *
     * @access private
     * @return string
     */
    function AddrFormat($addr)
    {
        if (empty($addr[1])) {
            $formatted = $addr[0];
        } else {
            $formatted = sprintf('%s <%s>', '=?' . $this->CharSet . '?B?' . base64_encode($addr[1]) . '?=', $addr[0]);
        }
        return $formatted;
    }
}

?>

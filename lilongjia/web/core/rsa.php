<?php

class RSA {

    var $privatekey;    //resource or string private key
    var $publickey;     //ditto public
    var $plaintext;
    var $crypttext;
    var $ekey;          //ekey - set by encryption, required by decryption
    var $privkeypass;   //password for private key
    var $csr;           //certificate signing request string generated with keys
    var $config;

    function OpenSSL() {
        DEFINE("OPEN_SSL_CONF_PATH", "C:/php/openssl/openssl.cnf"); //point to your config file
        $this->config = array("config" => OPEN_SSL_CONF_PATH);
    }

    function readf($path) {
        //return file contents
        $fp = fopen($path, "r");
        $ret = fread($fp, 8192);
        fclose($fp);
        return $ret;
    }

    //privatekey can be text or file path
    function set_privatekey($privatekey, $isFile=0, $key_password="") {

        if ($key_password)
            $this->privkeypass = $key_password;

        if ($isFile
            )$privatekey = $this->readf($privatekey);

        $this->privatekey = openssl_get_privatekey($privatekey, $this->privkeypass);
    }

    //publickey can be text or file path
    function set_publickey($publickey, $isFile=0) {

        if ($isFile
            )$publickey = $this->readf($publickey);

        $this->publickey = openssl_get_publickey($publickey);
    }

    function set_ekey($ekey) {
        $this->ekey = $ekey;
    }

    function set_privkeypass($pass) {
        $this->privkeypass = $pass;
    }

    function set_plain($txt) {
        $this->plaintext = $txt;
    }

    function set_crypttext($txt) {
        $this->crypttext = $txt;
    }

    function encrypt($plain="") {

        if ($plain)
            $this->plaintext = $plain;

        openssl_seal($this->plaintext, $this->crypttext, $ekey, array($this->publickey));

        $this->ekey = $ekey[0];
    }

    function decrypt($crypt="", $ekey="") {

        if ($crypt
            )$this->crypttext = $crypt;
        if ($ekey
            )$this->ekey = $ekey;

        openssl_open($this->crypttext, $this->plaintext, $this->ekey, $this->privatekey);
    }

    function do_csr($countryName = "UK", $stateOrProvinceName = "London", $localityName = "Blah", $organizationName = "Blah1", $organizationalUnitName = "Blah2", $commonName = "Joe Bloggs", $emailAddress = "openssl@domain.com") {
        $dn = Array(
            "countryName" => $countryName,
            "stateOrProvinceName" => $stateOrProvinceName,
            "localityName" => $localityName,
            "organizationName" => $organizationName,
            "organizationalUnitName" => $organizationalUnitName,
            "commonName" => $commonName,
            "emailAddress" => $emailAddress
        );
        $privkey = openssl_pkey_new($this->config);
        $csr = openssl_csr_new($dn, $privkey, $this->config);
        DEFINE("OPEN_SSL_CERT_DAYS_VALID", 365); //1 year
        $sscert = openssl_csr_sign($csr, null, $privkey, OPEN_SSL_CERT_DAYS_VALID, $this->config);
        openssl_x509_export($sscert, $this->publickey);
        openssl_pkey_export($privkey, $this->privatekey, $this->privkeypass, $this->config);
        openssl_csr_export($csr, $this->csr);
    }

    function get_plain() {
        return $this->plaintext;
    }

    function get_crypt() {
        return $this->crypttext;
    }

    function get_ekey() {
        return $this->ekey;
    }

    function get_privatekey() {
        return $this->privatekey;
    }

    function get_privkeypass() {
        return $this->privkeypass;
    }

    function get_publickey() {
        return $this->publickey;
    }

}

//USAGE

$pass = "zPUp9mCzIrM7xQOEnPJZiDkBwPBV9UlITY0Xd3v4bfIwzJ12yPQCAkcR5BsePGVw
RK6GS5RwXSLrJu9Qj8+fk0wPj6IPY5HvA9Dgwh+dptPlXppeBm3JZJ+92l0DqR2M
ccL43V3Z4JN9OXRAfGWXyrBJNmwURkq7a2EyFElBBWK03OLYVMevQyRJcMKY0ai+
tmnFUSkH2zwnkXQfPUxg9aV7TmGQv/3TkK1SziyDyNm7GwtyIlfcigCCRz3uc77U
Izcez5wgmkpNElg/D7/VCd9E+grTfPYNmuTVccGOes+n8ISJJdW0vYX1xwWv5l
bK22CwD/l7SMBOz4M9XH0Jb0OhNxLza4XMDu0ANMIpnkn1KOcmQ4gB8fmAbBt";

$ossl = new RSA;

$ossl->set_privkeypass($pass);

//create a key pair
$ossl->do_csr();
echo "Generated certificate signing request<br><br>";

$privatekey = $ossl->get_privatekey();
echo "Private Key is:<BR><BR><TEXTAREA ROWS=20 COLS=75>" . HTMLENTITIES($privatekey) . "</TEXTAREA>";

$publickey = $ossl->get_publickey();
echo "<br><br>Public Key is:<br><br><TEXTAREA ROWS=20 COLS=75>" . HTMLENTITIES($publickey) . "</TEXTAREA><br><br>";


//wipe clean and start again
unset($ossl);
$ossl = new RSA;

//get just the public key
$ossl->set_publickey($publickey);

$testtext = "<b>I am secret</b>";

echo "Testing with " . $testtext . "<br><br>";
//encrypt some text
$ossl->encrypt($testtext);


//get the encrypted text
$crypt = $ossl->get_crypt();

echo "Encrypted text is:<input size=65 value=\"" . htmlentities($crypt) . "\"><br><br>";

//get the envelope key also needed to decrypt the encrypted text
$ekey = $ossl->get_ekey();

echo "Envelope Key is: <input size=65 value=\"" . htmlentities($ekey) . "\"><br><br>";

//wipe clean and start again
unset($ossl);
$ossl = new RSA;

//get the private key
$ossl->set_privatekey($privatekey, false, $pass);

$ossl->decrypt($crypt, $ekey);

echo "Text decrypted again to: " . $ossl->get_plain();
?>
<?php
class RSA
{

	public static function decrypt($data)
	{
		$decrypted = null;
		try {
			$rsa = new Crypt_RSA();
			$rsa->setEncryptionMode(CRYPT_RSA_ENCRYPTION_PKCS1);
			
			//decode and decrypt
			$decoded =  base64_decode($data);

			$rsa->loadKey('MIICXAIBAAKBgQCHPKW5c+4I0xfCURdOgAjdoXvhMhXPR8dMs3mOHsDolG8nmdnn ETV2yw1x8ol8KsGl6p4D6+tQEYmaqdvhpc6xWCAUHxQRSXnKWvoJNIgtF4J98WaF y1Xdys6tx1GbsM1/ng5FJT2sRypETWDNwePn3UIdNvoKCu68i0G9/n61twIDAQAB AoGAL1qJHRr+6pAf7aa/ZnlmoVR+MCh9gos7uhvOIHmcStRO56rzpflcozAOkSvA AH5oOFSi5/Sf/PVnHYUEvUp1yRfEJw8OlQkEoHVyK9+brTUdnDqT0myArZWZmQul Lxn0yT854MF596vPNxlUfA9OzaE0iEbV0MoZ4ZCqG/rzkuECQQCgyk5om4NF9s9+ GA4WQpq6FD8AYKSa4k7lYqhdssIhg5MtF2KFRCtBRdv6Hzft8jENz8QDGBe2LhOf zNMQiRJ9AkEA11DJpfyi/aXE5BUPIkr2RCSPFOYkTE5R07dFHqybizq3VxfRI9lG DoRNdNEqVZXqNyuPeIiSWUY4FdoR6vmLQwJAEP9R6p0F849zv9CrLI897A3X2yJc RENIM9eKFN2gyAowtMOUFqJuMChCaN6D+xNvPBaKkgkp+IhGas4sQcM7wQJAIO7S aPkDVRiNzPULo4sjr7iHygKJesJf8aoOgGqWP+1zLXcPHhSVipLh5gQ4HW8Yq+eV wjHhcJY07eK06uPQUQJBAImx+rQytDK5JMc3PT1ChFL3lxbopSyr43ahS+/SJjTH WZ+cJ19KEKuuroyY0XCxlBEvxnhFq5WcsLx6TEhMJlU=');

			$decrypted = $rsa->decrypt($decoded);
		} catch (Exception $e) {
			return null;
		}
		
		return $decrypted;
	}

	public static function encrypt($data){
		$encrypted = null;
		try {
			$rsa = new Crypt_RSA();
			$rsa->setEncryptionMode(CRYPT_RSA_ENCRYPTION_PKCS1);
			
			//encode and encrypt

			$rsa->loadKey('MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCHPKW5c+4I0xfCURdOgAjdoXvh MhXPR8dMs3mOHsDolG8nmdnnETV2yw1x8ol8KsGl6p4D6+tQEYmaqdvhpc6xWCAU HxQRSXnKWvoJNIgtF4J98WaFy1Xdys6tx1GbsM1/ng5FJT2sRypETWDNwePn3UId NvoKCu68i0G9/n61twIDAQAB');

			$encrypted = $rsa->encrypt($data);

			$encoded =  base64_encode($encrypted);
			
		} catch (Exception $e) {
			return null;
		}
		
		return $encoded;
	}


}
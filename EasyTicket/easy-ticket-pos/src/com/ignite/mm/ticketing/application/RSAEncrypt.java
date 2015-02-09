package com.ignite.mm.ticketing.application;
import java.security.KeyFactory;
import java.security.PublicKey;
import java.security.spec.X509EncodedKeySpec;

import javax.crypto.Cipher;
import android.util.Base64;
 
public class RSAEncrypt {
	
	/*
	 * Function to encrypt the data. 
	 * 
	 */
 
	public static String encrypt( String data )
	{
		String encoded = null;
		try {
			
		    Cipher cipher = Cipher.getInstance("RSA/ECB/PKCS1Padding");
		    cipher.init(Cipher.ENCRYPT_MODE, getPublicKeyFromString(SecureKey.getPEMKey()));

		    byte[] encrypted = cipher.doFinal(data.getBytes());
		    encoded = Base64.encodeToString(encrypted, Base64.DEFAULT);
		    
		}
		catch (Exception e) {
		    e.printStackTrace();
		}
		return encoded;
	}
	
	public static PublicKey getPublicKeyFromString(String stringKey) throws Exception {
	    byte[] keyBytes = stringKey.getBytes();
	    byte[] decode = Base64.decode(keyBytes, Base64.DEFAULT);
	    KeyFactory fact = KeyFactory.getInstance("RSA");
	    X509EncodedKeySpec x509KeySpec = new X509EncodedKeySpec(decode);
	    return (PublicKey) fact.generatePublic(x509KeySpec);
	}
}
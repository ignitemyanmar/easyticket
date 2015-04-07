package com.ignite.mm.ticketing.application;
import java.io.UnsupportedEncodingException;
import java.security.InvalidAlgorithmParameterException;
import java.security.InvalidKeyException;
import java.security.NoSuchAlgorithmException;

import javax.crypto.spec.SecretKeySpec;
import javax.crypto.spec.IvParameterSpec;
 
import javax.crypto.Cipher;
import javax.crypto.NoSuchPaddingException;

import android.util.Base64;
import android.util.Log;
 
public class AES {
  static String plaintext = "Hello"; /*Note null padding*/
  private static String encoded;
  
  public static void test() {
    try {
      
      System.out.println("==Java==");
      System.out.println("plain:   " + plaintext);
 
      byte[] cipher = encrypt(plaintext);
      encoded = Base64.encodeToString(cipher, Base64.DEFAULT);
      Log.i("","cipher encoded:  "+ encoded);
            
      byte[] decode = Base64.decode(encoded.getBytes("UTF-8"), Base64.DEFAULT);
      String decrypted = decrypt(decode);
 
      Log.i("","decrypt: " + decrypted);
 
    } catch (Exception e) {
      e.printStackTrace();
    } 
  }
  
  public static Cipher getEndcodeCipher() {
		String secretKeyString = getSecretKey();
		String ivString = getIV();
		Cipher cipher = null;
		try {
			byte[] secretKey = secretKeyString.getBytes("UTF-8");
			byte[] iv = ivString.getBytes("UTF-8");
			
			cipher = Cipher.getInstance("AES/CBC/PKCS5Padding");
			cipher.init(Cipher.ENCRYPT_MODE, new SecretKeySpec(secretKey, 0,
					32, "AES"), new IvParameterSpec(iv, 0, 16));
		} catch (NoSuchAlgorithmException e) {
			e.printStackTrace();
		} catch (NoSuchPaddingException e) {
			e.printStackTrace();
		} catch (InvalidKeyException e) {
			e.printStackTrace();
		} catch (InvalidAlgorithmParameterException e) {
			e.printStackTrace();
		} catch (UnsupportedEncodingException e) {
			e.printStackTrace();
		}
		return cipher;
	}
  
  public static Cipher getDecodeCipher() {
		String secretKeyString = getSecretKey();
		String ivString = getIV();
		Cipher cipher = null;
		try {
			byte[] secretKey = secretKeyString.getBytes("UTF-8");
			byte[] iv = ivString.getBytes("UTF-8");
			
			cipher = Cipher.getInstance("AES/CBC/PKCS5Padding");
			cipher.init(Cipher.DECRYPT_MODE, new SecretKeySpec(secretKey, 0,
					32, "AES"), new IvParameterSpec(iv, 0, 16));
		} catch (NoSuchAlgorithmException e) {
			e.printStackTrace();
		} catch (NoSuchPaddingException e) {
			e.printStackTrace();
		} catch (InvalidKeyException e) {
			e.printStackTrace();
		} catch (InvalidAlgorithmParameterException e) {
			e.printStackTrace();
		} catch (UnsupportedEncodingException e) {
			e.printStackTrace();
		}
		return cipher;
	}
 
  private static String getIV() {
	// TODO Auto-generated method stub
	return "5f3c4741672c6131267c7d4975";
}

private static String getSecretKey() {
	// TODO Auto-generated method stub
	return "dYzxXzu8MYyWyhEDawCumvhegFQquPwj";
}

public static byte[] encrypt(String plainText) throws Exception {
    Cipher cipher = getEndcodeCipher();
    return cipher.doFinal(plainText.getBytes("UTF-8"));
  }
 
  public static String decrypt(byte[] cipherText) throws Exception{
    Cipher cipher = getDecodeCipher();
    return new String(cipher.doFinal(cipherText),"UTF-8");
  }
}
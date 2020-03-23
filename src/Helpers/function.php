<?php
    
    /**
     * encrypt
     *
     * @param  mixed $planeString
     * @return string
     */
    function encrypt($planeString)
    {
        $plaintext = $planeString;
        $key = SECRET_ENCRYPTION_KEY;
        $ivlen = openssl_cipher_iv_length($cipher="aes-128-cbc");
        $ciphertext_raw = openssl_encrypt($plaintext, $cipher, $key, $options=OPENSSL_RAW_DATA, $key);
        $ciphertext = base64_encode($ciphertext_raw);
        return $ciphertext;
    }

  
  /**
   * decrypt
   *
   * @param  mixed $cipherString
   * @return string
   */
  function decrypt($cipherString)
  {
      $key = SECRET_ENCRYPTION_KEY;
      $c = base64_decode($cipherString);
      $ivlen = openssl_cipher_iv_length($cipher="aes-128-cbc");
      $ciphertext_raw = $c;
      $original_plaintext = openssl_decrypt($ciphertext_raw, $cipher, $key, $options=OPENSSL_RAW_DATA, $key);
      return $original_plaintext;
  }

  
  /**
   * calculateVotePercentage
   *
   * @param  mixed $total
   * @param  mixed $value
   * @return void
   */
  function calculateVotePercentage($total, $value)
  {
      return round(($value*100)/$total);
  }

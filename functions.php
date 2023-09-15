<?php

function setUserConfig($file_path, $key='', $value='') {
    $file = $file_path;
    if (file_exists( $file )) {
        $user_data = file_get_contents( $file );
        $user_data = json_decode( $user_data, TRUE );
    }else{
        $user_data = [];
    }
    $user_data[$key] = $value;
    write_file( $file, json_encode( $user_data ) );

    return TRUE;
}

function getUserConfig($file_path, $key='') {
    $file = $file_path;
    if (file_exists( $file )) {
        $user_data = file_get_contents( $file );
        $user_data = json_decode( $user_data, TRUE );
    }else{
        $user_data = [];
    }

    if (array_key_exists($key, $user_data)) {
        return $user_data[$key];
    }

    return FALSE;
}

function write_file( $path, $data, $mode = 'wb') {
    if ( ! $fp = @fopen( $path, $mode ) ) return FALSE;

    flock( $fp, LOCK_EX );

    for ( $result = $written = 0, $length = strlen( $data ); $written < $length; $written += $result ) {
        if ( ( $result = fwrite( $fp, substr( $data, $written ) ) ) === FALSE ) break;
    }

    flock( $fp, LOCK_UN );
    fclose( $fp );

    return is_int( $result );
}

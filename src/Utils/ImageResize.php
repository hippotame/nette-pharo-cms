<?php
 namespace Pharo;
//
// $Id: image_resize.class.php 500 2012-09-13 00:54:13Z hippo $
//


class ImageResize {

    /**
     * Is the Input Image loaded?
     *
     * @var    bool
     * @access protected
     */
    protected $_is_loaded = false;

    /**
     * Has the image been processed?
     *
     * @var    bool
     * @access protected
     */
    protected $_is_processed = false;

    /**
     * This var holds the input image file data
     *
     * @var    mixed
     * @access protected
     */
    protected $_input_data = null;

    /**
     * This var holds the output image file data
     *
     * @var    mixed
     * @access protected
     */
    protected $_output_data = null;

    /**
     * Should we maintain aspect?
     *
     * @var    bool
     * @access protected
     */
    protected $_maintain_aspect = true;

    /**
     * JPG compression. Default is 100%
     *
     * @var    int
     * @access protected
     */
    protected $_compression = 100;

    /**
     * The value of the output width, in pixels (if aspect is being maintained, this is the max width allowed)
     *
     * @var    int
     * @access protected
     */
    protected $_output_width = 800;

    /**
     * The value of the output height, in pixels (if aspect is being maintained, this is the max height allowed)
     *
     * @var    int
     * @access protected
     */
    protected $_output_height = 600;
    
    protected $output_format; 

    /**
     * Constructor
     *
     * @access public
     * @return void
     */
    function __construct ( ) {

    }

    /**
     * loadFromFormField
     *
     * @access public
     * @return bool
     */
    public function loadFromFormField ( $field_array ) {

        if ( is_array ( $field_array ) && count ( $field_array ) > 0 ) {
            //check that file exists
            if ( isset ( $field_array ['tmp_name'] ) && file_exists ( $field_array ['tmp_name'] ) ) {
                return $this->loadFromFile ( $field_array ['tmp_name'] );
            }
        }
        return false;
    }

    /**
     * loadFromFile
     *
     * @access public
     * @return bool
     */
    public function loadFromFile ( $location ) {

        if ( is_file ( $location ) ) {
            $data = false;
            $info = getimagesize ( $location );
            //[0] = w, [1] = h, [2] = type, [3] = attr
            switch ( $info [2] ) {
                case 1 :
                    // Gif File
                    $this->output_format = 1;
                    $data = @imagecreatefromgif ( $location );
                    break;
                
                case 2 :
                    // Jpg File
                    $this->output_format = 2;
                    $data = @imagecreatefromjpeg ( $location );
                    break;
                
                case 3 :
                    $this->output_format = 3;
                    // Png File
                    $data = @imagecreatefrompng ( $location );
                            @imagesavealpha($data, true);
                    break;
            }
            
            if ( $data ) {
                $this->_setInputData ( $data );
                return true;
            }
        }
        //print 'Cannot Load From File: ' . $location;
        return false;
    }

    /**
     * loadFromRaw
     *
     * @access public
     * @return bool
     */
    public function loadFromRaw ( $contents ) {

        $data = @imagecreatefromstring ( $contents );
        if ( $data ) {
            $this->_setInputData ( $data );
            return true;
        }
        return false;
    }

    /**
     * _setInputData
     *
     * @access protected
     * @return void
     */
    protected function _setInputData ( $data ) {

        $this->_input_data = $data;
        $this->_setIsLoaded ( ( $data ? true : false ) );
        $this->_setIsProcessed ( false );
    }

    /**
     * _setOutputData
     *
     * @access protected
     * @return void
     */
    protected function _setOutputData ( $data ) {

        $this->_output_data = $data;
        $this->_setIsProcessed ( true );
    }

    /**
     * getOutputData
     *
     * @access public
     * @return string
     */
    public function getOutputData ( ) {

        $this->_process ( );
        return $this->_output_data;
    }

    /**
     * _setIsLoaded
     *
     * @access protected
     * @return void
     */
    protected function _setIsLoaded ( $bool ) {

        $this->_is_loaded = $bool;
    }

    /**
     * isLoaded
     *
     * @access public
     * @return bool
     */
    public function isLoaded ( ) {

        return $this->_is_loaded;
    }

    /**
     * _setIsProcessed
     *
     * @access protected
     * @return void
     */
    protected function _setIsProcessed ( $bool ) {

        $this->_is_processed = $bool;
    }

    /**
     * isProcessed
     *
     * @access public
     * @return bool
     */
    public function isProcessed ( ) {

        return $this->_is_processed;
    }

    /**
     * setMaintainAspect
     *
     * @access public
     * @return void
     */
    public function setMaintainAspect ( $bool = true ) {

        $this->_maintain_aspect = $bool;
    }

    /**
     * getMaintainAspect
     *
     * @access public
     * @return bool
     */
    protected function getMaintainAspect ( ) {

        return $this->_maintain_aspect;
    }

    /**
     * setOutputDimensions
     *
     * @access public
     * @param int $width  The number of Pixels you want for the width. Default: 800
     * @param int $height The number of Pixels you want for the height. Default: 600
     * @return void
     */
    public function setOutputDimensions ( $width = 800, $height = 600 ) {

        $this->_output_width = intval ( $width );
        $this->_output_height = intval ( $height );
    }

    /**
     * getOutputWidth
     *
     * @access public
     * @return int Pixels
     */
    public function getOutputWidth ( ) {

        return $this->_output_width;
    }

    /**
     * getOutputHeight
     *
     * @access public
     * @return int Pixels
     */
    public function getOutputHeight ( ) {

        return $this->_output_height;
    }

    /**
     * setJpgCompression
     *
     * @access public
     * @param  int $percent The percentage (5 to 100) the output JPG should be compressed with. Default: 75
     * @return void
     */
    public function setJpgCompression ( $percent = 75 ) {

        if ( $percent > 100 ) {
            $percent = 100;
        } else if ( $percent < 5 ) {
            $percent = 5;
        }
        $this->_compression = intval ( $percent );
    }

    /**
     * getJpgCompression
     *
     * @access public
     * @return int
     */
    public function getJpgCompression ( ) {

        return $this->_compression;
    }

    /**
     * _process
     *
     * @access private
     * @param  bool $force Setting to True will force processing again, even if it's already been done.
     * @return bool
     */
    protected function _process ( $force = false ) {

        if ( $this->isLoaded ( ) ) {
            if ( ! $this->isProcessed ( ) || $force ) {
                
                $image_width = imagesx ( $this->_input_data );
                $image_height = imagesy ( $this->_input_data );
                
                if ( $this->getMaintainAspect ( ) && ! $this->getOutputWidth ( ) && ! $this->getOutputHeight ( ) ) {
                    print 'Error: When maintaining aspect, at least the output width or height must be set!';
                    return false;
                } else if ( ! $this->getMaintainAspect ( ) && ( ! $this->getOutputWidth ( ) || ! $this->getOutputHeight ( ) ) ) {
                    print 'Error: When not maintaining aspect, both output width and height must be set!';
                    return false;
                }
                
                if ( ( $this->getOutputWidth ( ) && $image_width > $this->getOutputWidth ( ) ) || ( $this->getOutputHeight ( ) && $image_height > $this->getOutputHeight ( ) ) ) {
                    //have to resize..
                    if ( $this->getMaintainAspect ( ) ) {
                        //Maintain Aspect
                        //get the proportional dimensions within the minimums..
                        $w_diff = $this->getOutputWidth ( ) / $image_width;
                        $h_diff = $this->getOutputHeight ( ) / $image_height;
                        $resized_width = $this->getOutputWidth ( );
                        $resized_height = $this->getOutputHeight ( );
                        if ( $w_diff < $h_diff ) {
                            $resized_height = ceil ( $image_height * $w_diff );
                        } else {
                            $resized_width = ceil ( $image_width * $h_diff );
                        }
                    } else {
                        // Not maintaining aspect
                        $resized_width = $this->getOutputWidth ( );
                        $resized_height = $this->getOutputHeight ( );
                    }
                    
                    $input_image = imagecreatetruecolor ( $resized_width, $resized_height );
                    
                    if($this->output_format == 3) {
                            imagealphablending($input_image, false);
                            imagesavealpha($input_image,true);
                            $transparent = imagecolorallocatealpha($input_image, 255, 255, 255, 127); //seting transparent background
                            imagefilledrectangle($input_image, 0, 0, $resized_width, $resized_height, $transparent);
                    }
                    
                    imagecopyresampled ( $input_image, $this->_input_data, 0, 0, 0, 0, $resized_width, $resized_height, $image_width, $image_height );
                    $image_width = $resized_width;
                    $image_height = $resized_height;
                }
                
                // reset actual dimensions
                $this->setOutputDimensions ( $image_width, $image_height );
                // Process Out
                ob_start ( );
                //yosi
                
                switch($this->output_format) {
                    case 3: { //PNG
                          imagepng ( ( isset ( $input_image ) ? $input_image : $this->_input_data ), null, 0 );
                    } break;
                    default: {
                          imagejpeg ( ( isset ( $input_image ) ? $input_image : $this->_input_data ), null, $this->getJpgCompression ( ) );
                    } break;
                    
                }
                
                //imagejpeg ( ( isset ( $input_image ) ? $input_image : $this->_input_data ), null, $this->getJpgCompression ( ) );
                $ob = ob_get_contents ( );
                ob_end_clean ( );
                $this->_setOutputData ( $ob );
                return true;
            }
            return true;
        }
        //print 'Cannot Process, Not Loaded';
        return false;
    }

    /**
     * saveImage
     *
     * @access public
     * @param  string $destination File Location to write JPG to.
     * @return bool
     */
    public function saveImage ( $destination ) {

        if ( $this->_process ( ) ) {
            if ( strlen ( $this->_output_data ) > 0 && file_put_contents ( $destination, $this->_output_data ) ) {
                return true;
            }
            print 'Cannot Save File: ' . $destination;
        } else {
            print 'Cannot Save File, not processed'.$destination;
        }
        return false;
    }

}



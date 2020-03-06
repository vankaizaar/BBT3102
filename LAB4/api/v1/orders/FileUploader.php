<?php
# Refer to https://owasp.org/www-community/vulnerabilities/Unrestricted_File_Upload
#   FORM VALIDATION ERROR CODES
#   0 => 'There is no error, the file uploaded with success'
#   1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini'
#   2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form'
#   3 => 'The uploaded file was only partially uploaded'
#   4 => 'No file was uploaded'
#   6 => 'Missing a temporary folder'
#   7 => 'Failed to write file to disk.'
#   8 => 'A PHP extension stopped the file upload.'


class FileUploader
{
    private static $target_directory = "uploads/";
    private static $size_limit = 50000;
    private $uploadOK = false;
    private $file_original_name;
    private $file_temp_name;
    private $file_type;
    private $file_extension;
    private $file_size;
    private $file_error;
    private $final_file_name;

    public function __construct($fileName, $fileTmpName, $fileSize, $fileType, $fileExtension, $fileErrors)
    {
        $this->file_original_name = $fileName;
        $this->file_type = $fileType;
        $this->file_size = $fileSize;
        $this->file_temp_name = $fileTmpName;
        $this->file_extension = $fileExtension;
        $this->file_error = $fileErrors;
        $this->final_file_name = self::$target_directory . $this->file_original_name;
    }

    #getters & setters
    public function setOriginalName($name)
    {
        $this->file_original_name = $name;
    }

    public function getOriginalName()
    {
        return $this->file_original_name;
    }

    public function setFileType($type)
    {
        $this->file_type = $type;
    }

    public function getFileType()
    {
        return $this->file_type;
    }

    public function setFileSize($size)
    {
        $this->file_size = $size;
    }

    public function getFileSize()
    {
        return $this->file_size;
    }

    public static function getFileSizeLimit()
    {
        return (static::$size_limit / 1000);
    }

    public function setFinalFileName($final_name)
    {
        $this->final_file_name = self::$target_directory . $final_name;
    }

    public function getFinalFileName()
    {
        return $this->final_file_name;
    }

    public function getFileError()
    {
        return $this->file_error;
    }

    public function getFileTempName()
    {
        return $this->file_temp_name;
    }

    #methods
    public function uploadFile(FileUploader $fileUploader)
    {
        if ($fileUploader->getFileError() == 0) {
            if ($fileUploader->moveFile($fileUploader)) {
                return $fileUploader->saveFilePathTo($fileUploader);
            } else {
                $message = "There was an error saving your upload. Please try again.";
                $fileUploader->createUploadFormErrorSessions($message);
            }
        } else {
            $message = "There was an error processing your upload. Please try again.";
            $fileUploader->createUploadFormErrorSessions($message);
        }
    }

    /**
     * @param FileUploader $fileUploader
     * @return bool
     *
     * Reference: https://www.php.net/manual/en/function.file-exists
     *
     * This function determines if a file already exists in uploads directory with the same name and extension.
     */
    public function fileAlreadyExists(FileUploader $fileUploader)
    {

        return file_exists($fileUploader->getFinalFileName());
    }

    public function saveFilePathTo(FileUploader $fileUploader)
    {
        $fileName = $fileUploader->getFinalFileName();
        $fileSize = $fileUploader->getFileSize();

        #$fileQuery = "INSERT INTO uploads (file_name,user_id,file_size) VALUES ('$fileName','$user_id','$fileSize'))";
        $fileValues = ["file_name" => $fileName, "file_size" => $fileSize];
        return $fileValues;
    }

    /**
     * @param FileUploader $fileUploader
     * @return bool
     *
     * Reference: https://www.php.net/manual/en/function.move-uploaded-file
     *
     * This function is responsible for moving the uploaded file from the temp dir to the
     * final folder on the server.
     *
     * If saving is successful then true will be returned else it will be false.
     *
     */
    public function moveFile(FileUploader $fileUploader)
    {
        return move_uploaded_file($fileUploader->getFileTempName(), $fileUploader->getFinalFileName());
    }

    /**
     * @param FileUploader $fileUploader
     * @return bool
     *
     * Reference: https://www.php.net/manual/en/function.exif-imagetype
     *
     * This function checks that the submitted file is of the right type using the exif inbuilt library.
     * This library can determine 18 different image types. If a none image is submitted then false will be
     * returned. If it is determined to be an image i.e < or = 18
     *
     */

    public function fileTypeIsCorrect(FileUploader $fileUploader)
    {
        return ((exif_imagetype($fileUploader->getFileTempName()) <= 18) && (exif_imagetype($fileUploader->getFileTempName()) != FALSE)) ? TRUE : FALSE;
    }

    /**
     * @param FileUploader $fileUploader
     * @return bool
     *
     * Reference: https://www.php.net/manual/en/features.file-upload.errors.php
     *
     * This function checks that the form submitted is of the permitted size.
     * Checks done against the $_FILES['error'] value 2 which is computed from the form
     * via the hidden field MAX_FILE_SIZE, as this can be manipulated from the front end we
     * secure the upload via checking it against the static value in the class.
     * If both are true then the file is correct.
     *
     */
    public function fileSizeIsCorrect(FileUploader $fileUploader)
    {
        return ((($fileUploader->getFileSize()) <= static::$size_limit)) && (($fileUploader->getFileError()) != 2) ? TRUE : FALSE;
    }

    /**
     * @param FileUploader $fileUploader
     * @return bool
     *
     * Reference: https://www.php.net/manual/en/features.file-upload.errors.php
     *
     * This function checks that a file was submitted by evaluating the
     * $_FILES['error'] value. By default this should be 0 indicating a file was posted.
     * The error code 4 indicates that no file uploads were present.
     * Returns false if no file was received else true
     */
    public function fileWasSelected(FileUploader $fileUploader)
    {
        return (($fileUploader->getFileError()) == 4) ? FALSE : TRUE;
    }

    /**
     * Set errors upon file upload errors
     */

    public function createUploadFormErrorSessions($errorMessage)
    {
        session_start();
        $_SESSION['form_errors'] = $errorMessage;
    }
}

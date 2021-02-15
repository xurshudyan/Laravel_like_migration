<?php
namespace Core\SRC;
trait Helper
{
    /**
     * @brief for getting the file names from current folder
     * @descritpion this function returns an array of file names and if there are folders it will return a folder name as a array key and it's file names as values
     * @param $dir
     * @return array
     */
   public function dirToArray($dir) {
        $result = array();
        $cdir = scandir($dir);
        foreach ($cdir as $key => $value)
        {
            if (!in_array($value,array(".","..")))
            {
                if (is_dir($dir . DIRECTORY_SEPARATOR . $value))
                {
                    $result[$value] = $this->dirToArray($dir . DIRECTORY_SEPARATOR . $value);
                }
                else
                {
                    $result[] = $value;
                }
            }
        }
        return $result;
    }
}
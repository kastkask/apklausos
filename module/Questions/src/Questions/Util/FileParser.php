<?php

namespace Questions\Util;

class FileParser
{
    protected $_file;
    protected $_fileContent;

    public function parseFile($file)
    {
        $this->_file = $file;
        $this->_fileContent = $this->readFileContent($file['tmp_name']);
        var_dump(file_get_contents($file['tmp_name']));
        $emails = $this->extractEmails();
        return $this->removeDublicates($emails);
    }

    private function readFileContent($file_path)
    {
        return file_get_contents($file_path);
    }

    private function extractEmails()
    {
        $content = str_replace(array("\r\n", "\r"), "\n", trim($this->_fileContent));

        $lines = explode("\n", $content);
        $emails = array();
        foreach ($lines as $email)
        {
            if ($this->validateEmail($email))
            {
                $emails[] = $email;
            }
        }

        return $emails;
    }

    private function validateEmail($email)
    {
        $validator = new \Zend\Validator\EmailAddress();
        return $validator->isValid($email);
    }

    private function removeDublicates($emails)
    {
        return array_unique($emails);
    }

    public function removeCurrentEmails($collection, $emails)
    {
        foreach($collection as $email)
        {
            if (false !== $key = array_search($email->getEmail(), $emails))
            {
                unset($emails[$key]);
            }
        }
        return $emails;
    }
}
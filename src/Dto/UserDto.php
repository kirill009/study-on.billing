<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;

class UserDto
{
    /**
     * @Assert\NotBlank(message="Is mandatory")
     * @Assert\Email(message="Invalid email address")
     * @Serializer\Type("string")
     */
    private $email;

    /**
     * @Assert\NotBlank(message="Is mandatory")
     * @Assert\Length(min=6)
     * @Serializer\Type("string")
     */
    private $password;

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }


}
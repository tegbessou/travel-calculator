<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity("airport")
 */
class Airport
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer", name="id")
     *
     * @Assert\Type("int")
     * @Assert\NotBlank()
     */
    public int $id;

    /**
     * @ORM\Column(type="string", name="name")
     *
     * @Assert\Type("string")
     * @Assert\NotBlank()
     */
    public string $name;

    /**
     * @ORM\Column(type="float", name="latitutde")
     *
     * @Assert\Type("float")
     * @Assert\NotBlank()
     */
    public float $latitude;

    /**
     * @ORM\Column(type="float", name="longitude")
     *
     * @Assert\Type("float")
     * @Assert\NotBlank()
     */
    public float $longitude;

    /**
     * @ORM\Column(type="string", name="country_code")
     *
     * @Assert\Type("string")
     * @Assert\NotBlank()
     * @Assert\Country()
     */
    public string $countryCode;

    /**
     * @ORM\Column(type="string", name="city")
     *
     * @Assert\Type("string")
     * @Assert\NotBlank()
     */
    public string $city;

    /**
     * @ORM\Column(type="string", name="iota_code")
     *
     * @Assert\Type("string")
     * @Assert\NotBlank()
     */
    public string $iotaCode;
}
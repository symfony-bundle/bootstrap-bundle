<?php

namespace Anezi\Bundle\BootstrapBundle\Model;

class Site
{
    /**
     * @var string
     */
    private $title = 'Bootstrap Bundle for Symfony';

    /**
     * @var string
     */
    private $baseurl;

    /**
     * @var /stdClass
     */
    private $github;

    /**
     * @var string
     */
    private $expo = 'http://expo.getbootstrap.com/';

    /**
     * @var string
     */
    private $blog = 'https://twitter.com/HassanAmouhzi';

    /**
     * @var string
     */
    private $currentVersion = '3.2';

    /**
     * @var string
     */
    private $repo = 'https://github.com/symfony-bundle/bootstrap-bundle';

    /**
     * @var string[]
     */
    private $download;

    /**
     * @var \stdClass[]
     */
    private $data = array();

    /**
     * @var string
     */
    private $sassRepo = 'https://github.com/twbs/bootstrap-sass';

    /**
     * @var string
     */
    private $cdn;

    /**
     * @var \DateTime
     */
    private $time;

    /**
     * @return string
     */
    public function getBaseurl()
    {
        return $this->baseurl;
    }

    /**
     * @param string $baseurl
     */
    public function setBaseurl($baseurl)
    {
        $this->baseurl = $baseurl;
    }

    /**
     * @return /stdClass
     */
    public function getGithub()
    {
        return $this->github;
    }

    /**
     * @param /stdClass $github
     */
    public function setGithub($github)
    {
        $this->github = $github;
    }

    /**
     * @return string
     */
    public function getExpo()
    {
        return $this->expo;
    }

    /**
     * @param string $expo
     */
    public function setExpo($expo)
    {
        $this->expo = $expo;
    }

    /**
     * @return string
     */
    public function getBlog()
    {
        return $this->blog;
    }

    /**
     * @param string $blog
     */
    public function setBlog($blog)
    {
        $this->blog = $blog;
    }

    /**
     * @return string
     */
    public function getCurrent_Version()
    {
        return $this->currentVersion;
    }

    /**
     * @param string $currentVersion
     */
    public function setCurrent_Version($currentVersion)
    {
        $this->currentVersion = $currentVersion;
    }

    /**
     * @return string
     */
    public function getRepo()
    {
        return $this->repo;
    }

    /**
     * @param string $repo
     */
    public function setRepo($repo)
    {
        $this->repo = $repo;
    }

    /**
     * @return \stdClass[]
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param \stdClass[] $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return string[]
     */
    public function getDownload()
    {
        return $this->download;
    }

    /**
     * @param string[] $download
     */
    public function setDownload(array $download)
    {
        $this->download = $download;
    }

    /**
     * @return string
     */
    public function getSass_Repo()
    {
        return $this->sassRepo;
    }

    /**
     * @param string $sassRepo
     */
    public function setSass_Repo($sassRepo)
    {
        $this->sassRepo = $sassRepo;
    }

    /**
     * @return string
     */
    public function getCdn()
    {
        return $this->cdn;
    }

    /**
     * @param string $cdn
     */
    public function setCdn($cdn)
    {
        $this->cdn = $cdn;
    }

    /**
     * @return \DateTime
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @param \DateTime $time
     */
    public function setTime($time)
    {
        $this->time = $time;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

}

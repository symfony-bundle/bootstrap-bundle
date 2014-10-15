<?php

namespace Anezi\Bundle\BootstrapBundle\Controller;

use Anezi\Bundle\BootstrapBundle\Model\Page;
use Anezi\Bundle\BootstrapBundle\Model\Site;
use MyProject\Proxies\__CG__\OtherProject\Proxies\__CG__\stdClass;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Yaml\Yaml;

class DocsController extends Controller
{
    public function indexAction(Request $request, $slug)
    {
        //echo '<pre>';print_r($slug);die;
        $page = new Page();
        $page->setUrl($request->getRequestUri());
        $page->setTitle('Homepage');
        $page->setSlug($slug);

        $site = new Site();
        $site->setBaseurl($request->getBaseUrl());

        $github = new \stdClass();
        $github->user = 'symfony-bundle';
        $github->repo = 'bootstrap-bundle';
        $site->setGithub($github);

        $data = $site->getData();
        $data['showcase'] = Yaml::parse(__DIR__ . '/../Resources/data/showcase.yml');
        $data['translations'] = Yaml::parse(__DIR__ . '/../Resources/data/translations.yml');
        $site->setData($data);
        $site->setDownload(array(
            'dist' =>
                'https://github.com/twbs/bootstrap/releases/download/v3.2.0/bootstrap-3.2.0-dist.zip',
            'source' => 'https://github.com/twbs/bootstrap/archive/v3.2.0.zip',
            'sass' => 'https://github.com/twbs/bootstrap-sass/archive/v3.2.0.tar.gz'
        ));
        $site->setCdn(array(
            'css' => 'https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css',
            'css_theme' =>
                'https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css',
            'js' => 'https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js'
        ));

        $site->setTime(new \DateTime());

        /*$content = $this->render(
            'BootstrapBundle:pages:' . $slug . '.html.twig',
            array(
                'site' => $site
            )
        )->getContent();*/

        if($slug == 'index') {
            $file = 'home';
        } else {
            $file = 'default';
        }

        return $this->render(
            'BootstrapBundle:layouts:' . $file . '.html.twig',
            array(
                'page' => $page,
                'site' => $site,
                'slug' => $slug
                //'content' => $content
            )
        );
    }
}

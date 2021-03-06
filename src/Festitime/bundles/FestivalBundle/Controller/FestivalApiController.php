<?php

namespace Festitime\bundles\FestivalBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;
use Festitime\DatabaseBundle\Document\Festival;

class FestivalApiController extends FOSRestController
{
    public function getFestivalAction($id)
    {
        $festivalService = $this->get('festitime.festival_service');
        $festival = $festivalService->getFestival($id);

        if ($festival instanceof Festival) {
            return $this->view($festival, 200);
        }

        return $this->view(null, 204);
    }

    public function getFestivalsAction(Request $request)
    {
        $queryParams = $request->query->all();
        $festivalService = $this->container->get('festitime.festival_service');
        $festivals = $festivalService->getFestivals($queryParams);

        return $this->view($festivals, 200);
    }

    public function getFestivalsRandomPicturesAction($count = 20)
    {
        $festivalService = $this->container->get('festitime.festival_service');
        $pictures = $festivalService->getFestivalsRandomPictures($count);
        return $this->view($pictures, 200);
    }

    public function putFestivalAction($id)
    {
        $festivalService = $this->container->get('festitime.festival_service');
        $festivalService->putFestival($id);
        die('ok');
    }

    public function deleteFestivalAction($id)
    {
        $festivalService = $this->container->get('festitime.festival_service');
        $festivalService->deleteFestival($id);

        return $this->view(null, 204);
    }

    public function putFestivalArtistsAction($id, Request $request)
    {
        $idArtists = $request->request->get('artists');
        if (!empty($idArtists) && is_array($idArtists)) {
            $festivalService = $this->container->get('festitime.festival_service');
            $festival = $festivalService->linkFestivalArtists($id, $idArtists);

            if ($festival instanceof Festival) {
                return $this->view($festival, 201);
            }
        }

        return $this->view('a parameter is missing or missused', 422);
    }
}

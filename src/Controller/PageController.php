<?php

namespace App\Controller;

use App\Entity\Contacto;
use App\Entity\Provincia;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PageController extends AbstractController
{
    private $contactos = [

    ];

    #[Route('/page', name: 'app_page')]
    public function index(): Response
    {
        return $this->render('page/index.html.twig', [
            'controller_name' => 'PageController',
        ]);
    }
    #[Route('/', name: 'inicio')]
    public function inicio(): Response{
        return $this->render('inicio.html.twig');
    }
    #[Route('/contacto/insertar', name: 'insertar_contacto')]
    public function insertar(ManagerRegistry $doctrine){
        $entityManager = $doctrine->getManager();

            $contacto = new Contacto();
            $contacto->setNombre("Alex");
            $contacto->setTelefono("610803928");
            $contacto->setEmail("alex@gmail.com");
            $contacto->setProvinciaId("0");
            $entityManager->persist($contacto);
            $entityManager->flush();
        try {
            $entityManager->flush();
            return new Response("Contactos insertados");
        }catch (\Exception $e){
            return new Response("Error insertando objetos");
        }
    }
    #[Route('/contacto/{codigo}', name: 'ficha_contacto')]
public function ficha_contacto(ManagerRegistry$doctrine, $codigo): Response{
        $repositorio = $doctrine->getRepository(Contacto::class);
        $contacto = $repositorio->find($codigo);


        return $this->render('ficha_contacto.html.twig', ["contacto"=> $contacto]);

    }

    #[Route('/contacto/buscar/{texto}', name: 'buscar_contacto')]
    public function buscar_contacto(ManagerRegistry $doctrine, $texto): Response{
        $repositorio = $doctrine->getRepository(Contacto::class);

        $contactos = $repositorio->findByName($texto);

        return $this->render('lista_contactos.html.twig', ['contactos' => $contactos]);
    }
    #[Route('/contacto/update/{id}/{nombre}', name: 'modificar_contacto')]
    public function update(ManagerRegistry $doctrine, $id, $nombre): Response{
        $entityManager = $doctrine->getManager();
        $repositorio = $doctrine->getRepository(Contacto::class);
        $contacto = $repositorio->find($id);
        if($contacto){
            $contacto->setNombre($nombre);
            try {
                $entityManager->flush();
                return $this->render('ficha_contacto.html.twig', ["contacto"=> $contacto]);
            }catch (\Exception $e){
                return new Response("Error insertando objetos");
            }
        }else
            return $this->render('ficha_contacto.html.twig', ["contacto"=> null]);
    }

    #[Route('/contacto/delete/{id}', name: 'eliminar_contacto')]
    public function delete(ManagerRegistry $doctrine, $id): Response{
        $entityManager = $doctrine->getManager();
        $repositorio = $doctrine->getRepository(Contacto::class);
        $contacto = $repositorio->find($id);
        if($contacto){
            try {
                $entityManager->remove($contacto);
                $entityManager->flush();
                return new Response("Contacto eliminado");
            }catch (\Exception $e){
                return new Response("Error eliminando objeto");
            }
        }else
            return $this->render('ficha_contacto.html.twig', ["contacto"=> null]);
    }

    #[Route('/contacto/insertarConProvincia',name: 'insertar_contacto_provincia')]
    public function insertar_provincia(ManagerRegistry $doctrine): Response{
        $entityManager = $doctrine->getManager();
        $provincia = new Provincia();

        $provincia->setNombre("Alicante");
        $contacto = new Contacto();

        $contacto->setNombre("Alex");
        $contacto->setTelefono("610803928");
        $contacto->setEmail("alex@gmail.com");
        $contacto->setProvincia($provincia);

        $entityManager->persist($provincia);
        $entityManager->persist($contacto);

        $entityManager->flush();
        return $this->render('ficha_contacto.html.twig', ["contacto"=> $contacto]);
    }

}

<?php

namespace App\Controller;

use App\Entity\Contacto;
use App\Entity\Provincia;
use App\Form\ContactoType;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PageController extends AbstractController
{


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
    #[Route('/contacto/insertar/nuevoContacto', name: 'insertar_contacto')]
    public function insertar(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $repositorio = $doctrine->getRepository(Provincia::class);

        $provincia = $repositorio->findOneBy(["nombre" => "Alicante"]);

            $contacto = new Contacto();
            $contacto->setNombre("Paco");
            $contacto->setTelefono("629111833");
            $contacto->setEmail("paco@gmail.com");
            $contacto->setProvincia($provincia);
            $entityManager->persist($contacto);
            $entityManager->flush();
        try {
            $entityManager->flush();
            return new Response("Contactos insertados");
        }catch (Exception $e){
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
            }catch (Exception $e){
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
            }catch (Exception $e){
                return new Response("Error eliminando objeto");
            }
        }else
            return $this->render('ficha_contacto.html.twig', ["contacto"=> null]);
    }

    #[Route('/contacto/provincia/insertarConProvincia',name: 'insertar_contacto_provincia')]
    public function insertar_provincia(ManagerRegistry $doctrine): Response{
        $entityManager = $doctrine->getManager();
        $provincia = new Provincia();

        $provincia->setNombre("Castellón");
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
    #[Route('/contacto/provincia/insertarSinProvincia',name: 'insertar_sin_provincia')]
    public function insertar_sin_provincia(ManagerRegistry $doctrine): Response{
        $entityManager = $doctrine->getManager();
        $repositorio = $doctrine->getRepository(Provincia::class);

        $provincia = $repositorio->findOneBy(["nombre" => "Alicante"]);

        $contacto = new Contacto();

        $contacto->setNombre("Juan");
        $contacto->setTelefono("622938711");
        $contacto->setEmail("juan@gmail.com");
        $contacto->setProvincia($provincia);

        $entityManager->persist($contacto);

        $entityManager->flush();
        return $this->render('ficha_contacto.html.twig', ["contacto"=> $contacto]);
    }

    #[Route('/crearcontacto/nuevo',name: 'nuevo_contacto')]
    public function nuevo(ManagerRegistry $doctrine, Request $request): Response{
        $contacto = new Contacto();

        $formulario = $this->createForm(ContactoType::class, $contacto);

            $formulario->handleRequest($request);

            if($formulario->isSubmitted() && $formulario->isValid()){
                $contacto = $formulario->getData();
                $entityManager = $doctrine->getManager();
                $entityManager->persist($contacto);
                $entityManager->flush();
                return $this->redirectToRoute('ficha_contacto',['codigo'=>$contacto->getId()]);
            }

        return $this->render('nuevo.html.twig', array(
            'formulario' => $formulario->createView()
        ));
    }

    #[Route('/contacto/editar/{id}',name: 'editar_contacto')]
    public function editar(ManagerRegistry $doctrine, Request $request, $id): Response{
        $repositorio = $doctrine->getRepository(Contacto::class);
        $contacto = $repositorio->find($id);

        if ($contacto){
            $formulario = $this->createForm(ContactoType::class, $contacto);
            $formulario->handleRequest($request);
            if ($formulario->isSubmitted() && $formulario->isValid()) {
                $contacto = $formulario->getData();
                $entityManager = $doctrine->getManager();
                $entityManager->persist($contacto);
                $entityManager->flush();
                return $this->redirectToRoute('ficha_contacto', ["codigo" => $contacto->getId()]);
            }
            return $this->render('nuevo.html.twig', array(
                'formulario' => $formulario->createView()
            ));
        }else{
            return $this->render('ficha_contacto.html.twig', [
                'contacto' => NULL
            ]);
        }
    }
}

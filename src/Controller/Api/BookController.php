<?php

namespace App\Controller\Api;

use App\Entity\Book;
use App\Form\Model\BookDto;
use App\Form\Type\BookFormType;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use League\Flysystem\FilesystemOperator;

class BookController extends AbstractFOSRestController
{
    /**
     * @Rest\Get(path="/books", name="books.index")
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     */
    public function index(BookRepository $bookRepository)
    {
        return $bookRepository->findAll();
    }

    /**
     * @Rest\Post(path="/books", name="books.store")
     * @Rest\View(serializerGroups={"book"})
     */
    public function store(Request $request, EntityManagerInterface $em, FilesystemOperator $defaultStorage)
    {
        /* Crear un Dto para interactuar con el y no con la entidad directamente */
        $bookDto = new BookDto();

        /* Creamos un formulario para formatear y validar la data */
        $form = $this->createForm(BookFormType::class, $bookDto);

        /* Pasamos la request al form para que pueda usarla */
        $form->handleRequest($request);

        /* Validamos que haya sido enviado y que sea valida la data en la request */
        if ($form->isSubmitted() && $form->isValid()) {
            # Procesamos la imagen que viene en base64 dentro de la request
            $extension = explode('/', mime_content_type($bookDto->base64Image))[1];
            $data = explode(',', $bookDto->base64Image);
            $filename = sprintf('%s.%s', uniqid('book_', true), $extension);
            $defaultStorage->write($filename, base64_decode($data[1]));
            # Fin procesamiento de imagen

            /* Creamos una instancia del modelo */
            $book = new Book();

            /* Cargamos dentro de la instancia los datos correspondientes que vienen pro el Dto */
            $book->setTitle($bookDto->title);
            $book->setImage($filename);

            /* Decimos al EM que se encargue de la instancia y que la guarde */
            $em->persist($book);
            $em->flush();

            /* Retornamos el objeto que ha sido creado y guardado */
            return $book;
        }

        /* Retornamos el form en caso de que las validaciones hayan fallado */
        return $form;
    }
}

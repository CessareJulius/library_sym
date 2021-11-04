<?php

namespace App\Serializer;

use App\Entity\Book;
use Symfony\Component\HttpFoundation\UrlHelper;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;

class BookNormalizer implements ContextAwareNormalizerInterface
{
    //private $router;
    private $normalizer;
    private $urlHelper;

    public function __construct(ObjectNormalizer $normalizer, UrlHelper $urlHelper)
    {
        $this->normalizer = $normalizer;
        $this->urlHelper = $urlHelper;
    }

    public function normalize($book, string $format = null, array $context = [])
    {
        $data = $this->normalizer->normalize($book, $format, $context);

        if (!empty($book->getImage())) {
            $data['image'] = 'hola';
        }

        // Here, add, edit, or delete some data:
        /* $data['href']['self'] = $this->router->generate('topic_show', [
            'id' => $book->getId(),
        ], UrlGeneratorInterface::ABSOLUTE_URL); */

        return $data;
    }

    public function supportsNormalization($data, string $format = null, array $context = [])
    {
        return $data instanceof Book;
    }
}

<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    //je cree une fonction qui me permet de chercher dans ma BDD.
    public function searchByTitle($word)
    {

        // j'utilise la méthode createQueryBuilder
        // provenant de la classe parent pour instancer un objet
        // et je définis un alias pour la table book
        $queryBuilder = $this->createQueryBuilder('a');

        // je demande à Doctrine de créer une requête SQL
        // qui fait une requête SELECT sur la table book
        $query = $queryBuilder->select('a')
            // à condition que le titre du book
            // contiennent le contenu de $word (à un endroit ou à un autre, grâce à LIKE %xxxx%)
            ->where('a.title LIKE :word')
            // avec la methode setParameter je controle le contenu de la variable
            ->setParameter('word', '%'.$word.'%')
            // puis je recuper ma requete SQL
            ->getQuery();

        // je récupère les résultats de la requête SQL
        // et je les retourne
        return $query->getResult();


    }

    // /**
    //  * @return Article[] Returns an array of Article objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Article
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

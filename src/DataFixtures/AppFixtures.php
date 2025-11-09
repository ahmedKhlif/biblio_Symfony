<?php

namespace App\DataFixtures;

use App\Entity\Auteur;
use App\Entity\Categorie;
use App\Entity\Editeur;
use App\Entity\Livre;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Créer des auteurs réels
        $auteurs = [];
        $auteursData = [
            ['prenom' => 'Victor', 'nom' => 'Hugo', 'biographie' => 'Écrivain, dramaturge, poète, homme politique et intellectuel français, né le 26 février 1802 à Besançon et mort le 22 mai 1885 à Paris.'],
            ['prenom' => 'Albert', 'nom' => 'Camus', 'biographie' => 'Écrivain, philosophe, dramaturge et journaliste français, né le 7 novembre 1913 à Mondovi (Algérie) et mort le 4 janvier 1960 près de Sens (Yonne). Prix Nobel de littérature en 1957.'],
            ['prenom' => 'Simone', 'nom' => 'de Beauvoir', 'biographie' => 'Philosophe, romancière, épistolière, mémorialiste et essayiste française, née le 9 janvier 1908 à Paris et morte le 14 avril 1986 dans la même ville.'],
            ['prenom' => 'Marcel', 'nom' => 'Proust', 'biographie' => 'Écrivain français, né le 10 juillet 1871 à Auteuil (alors commune indépendante) et mort le 18 novembre 1922 à Paris.'],
            ['prenom' => 'Gustave', 'nom' => 'Flaubert', 'biographie' => 'Écrivain français, né le 12 décembre 1821 à Rouen et mort le 8 mai 1880 à Croisset.'],
            ['prenom' => 'Émile', 'nom' => 'Zola', 'biographie' => 'Écrivain et journaliste français, né le 2 avril 1840 à Paris et mort le 29 septembre 1902 dans la même ville.'],
            ['prenom' => 'Honoré', 'nom' => 'de Balzac', 'biographie' => 'Écrivain français, né le 20 mai 1799 à Tours et mort le 18 août 1850 à Paris.'],
            ['prenom' => 'Stendhal', 'nom' => '', 'biographie' => 'Écrivain français, né le 23 janvier 1783 à Grenoble et mort le 23 mars 1842 à Paris.'],
            ['prenom' => 'François', 'nom' => 'Mauriac', 'biographie' => 'Écrivain français, né le 11 octobre 1885 à Bordeaux et mort le 1er septembre 1970 à Paris. Prix Nobel de littérature en 1952.'],
            ['prenom' => 'André', 'nom' => 'Malraux', 'biographie' => 'Écrivain, homme politique et ministre français, né le 3 novembre 1901 à Paris et mort le 23 novembre 1976 dans la même ville.'],
        ];

        foreach ($auteursData as $data) {
            $auteur = new Auteur();
            $auteur->setPrenom($data['prenom']);
            $auteur->setNom($data['nom']);
            $auteur->setBiographie($data['biographie']);
            $manager->persist($auteur);
            $auteurs[] = $auteur;
        }

        // Créer des catégories réelles
        $categories = [];
        $categoriesData = [
            ['designation' => 'Roman', 'description' => 'Œuvres de fiction narrative en prose'],
            ['designation' => 'Philosophie', 'description' => 'Ouvrages traitant de questions philosophiques et existentielles'],
            ['designation' => 'Biographie', 'description' => 'Récits de vie d\'individus célèbres'],
            ['designation' => 'Poésie', 'description' => 'Recueils de poèmes et œuvres poétiques'],
            ['designation' => 'Théâtre', 'description' => 'Pièces de théâtre et œuvres dramatiques'],
            ['designation' => 'Essai', 'description' => 'Ouvrages d\'analyse et de réflexion'],
            ['designation' => 'Histoire', 'description' => 'Ouvrages historiques et récits du passé'],
            ['designation' => 'Science', 'description' => 'Ouvrages scientifiques et techniques'],
        ];

        foreach ($categoriesData as $data) {
            $categorie = new Categorie();
            $categorie->setDesignation($data['designation']);
            $categorie->setDescription($data['description']);
            $manager->persist($categorie);
            $categories[] = $categorie;
        }

        // Créer des éditeurs réels
        $editeurs = [];
        $editeursData = [
            ['nomEditeur' => 'Gallimard', 'pays' => 'France', 'adresse' => '5 rue Gaston-Gallimard, 75328 Paris Cedex 07', 'telephone' => '01 49 54 42 00'],
            ['nomEditeur' => 'Le Seuil', 'pays' => 'France', 'adresse' => '27 rue Jacob, 75006 Paris', 'telephone' => '01 40 43 20 00'],
            ['nomEditeur' => 'Flammarion', 'pays' => 'France', 'adresse' => '87 quai Panhard-et-Levassor, 75647 Paris Cedex 13', 'telephone' => '01 40 51 30 00'],
            ['nomEditeur' => 'Albin Michel', 'pays' => 'France', 'adresse' => '22 rue Huyghens, 75014 Paris', 'telephone' => '01 42 79 10 00'],
            ['nomEditeur' => 'Éditions du Seuil', 'pays' => 'France', 'adresse' => '27 rue Jacob, 75006 Paris', 'telephone' => '01 40 43 20 00'],
            ['nomEditeur' => 'Grasset', 'pays' => 'France', 'adresse' => '61 rue des Saints-Pères, 75006 Paris', 'telephone' => '01 44 39 23 00'],
        ];

        foreach ($editeursData as $data) {
            $editeur = new Editeur();
            $editeur->setNomEditeur($data['nomEditeur']);
            $editeur->setPays($data['pays']);
            $editeur->setAdresse($data['adresse']);
            $editeur->setTelephone($data['telephone']);
            $manager->persist($editeur);
            $editeurs[] = $editeur;
        }

        // Créer des livres réels avec données authentiques
        $livresData = [
            [
                'titre' => 'Les Misérables',
                'nbPages' => 1488,
                'dateEdition' => new \DateTime('1862-01-01'),
                'nbExemplaires' => 45,
                'prix' => 29.90,
                'isbn' => '9782070418945',
                'auteur' => 0, // Victor Hugo
                'categorie' => 0, // Roman
                'editeur' => 0, // Gallimard
            ],
            [
                'titre' => 'L\'Étranger',
                'nbPages' => 123,
                'dateEdition' => new \DateTime('1942-01-01'),
                'nbExemplaires' => 67,
                'prix' => 8.50,
                'isbn' => '9782070360022',
                'auteur' => 1, // Albert Camus
                'categorie' => 0, // Roman
                'editeur' => 0, // Gallimard
            ],
            [
                'titre' => 'Le Deuxième Sexe',
                'nbPages' => 587,
                'dateEdition' => new \DateTime('1949-01-01'),
                'nbExemplaires' => 23,
                'prix' => 24.50,
                'isbn' => '9782070322853',
                'auteur' => 2, // Simone de Beauvoir
                'categorie' => 1, // Philosophie
                'editeur' => 0, // Gallimard
            ],
            [
                'titre' => 'À la recherche du temps perdu',
                'nbPages' => 4211,
                'dateEdition' => new \DateTime('1913-01-01'),
                'nbExemplaires' => 12,
                'prix' => 89.90,
                'isbn' => '9782070414169',
                'auteur' => 3, // Marcel Proust
                'categorie' => 0, // Roman
                'editeur' => 0, // Gallimard
            ],
            [
                'titre' => 'Madame Bovary',
                'nbPages' => 528,
                'dateEdition' => new \DateTime('1857-01-01'),
                'nbExemplaires' => 34,
                'prix' => 12.90,
                'isbn' => '9782070419188',
                'auteur' => 4, // Gustave Flaubert
                'categorie' => 0, // Roman
                'editeur' => 0, // Gallimard
            ],
            [
                'titre' => 'Germinal',
                'nbPages' => 592,
                'dateEdition' => new \DateTime('1885-01-01'),
                'nbExemplaires' => 28,
                'prix' => 15.50,
                'isbn' => '9782070414046',
                'auteur' => 5, // Émile Zola
                'categorie' => 0, // Roman
                'editeur' => 0, // Gallimard
            ],
            [
                'titre' => 'Le Père Goriot',
                'nbPages' => 352,
                'dateEdition' => new \DateTime('1835-01-01'),
                'nbExemplaires' => 41,
                'prix' => 9.90,
                'isbn' => '9782070414047',
                'auteur' => 6, // Honoré de Balzac
                'categorie' => 0, // Roman
                'editeur' => 0, // Gallimard
            ],
            [
                'titre' => 'Le Rouge et le Noir',
                'nbPages' => 640,
                'dateEdition' => new \DateTime('1830-01-01'),
                'nbExemplaires' => 19,
                'prix' => 14.50,
                'isbn' => '9782070419189',
                'auteur' => 7, // Stendhal
                'categorie' => 0, // Roman
                'editeur' => 0, // Gallimard
            ],
            [
                'titre' => 'Thérèse Desqueyroux',
                'nbPages' => 192,
                'dateEdition' => new \DateTime('1927-01-01'),
                'nbExemplaires' => 15,
                'prix' => 11.90,
                'isbn' => '9782070419190',
                'auteur' => 8, // François Mauriac
                'categorie' => 0, // Roman
                'editeur' => 0, // Gallimard
            ],
            [
                'titre' => 'La Condition humaine',
                'nbPages' => 432,
                'dateEdition' => new \DateTime('1933-01-01'),
                'nbExemplaires' => 22,
                'prix' => 16.50,
                'isbn' => '9782070419191',
                'auteur' => 9, // André Malraux
                'categorie' => 0, // Roman
                'editeur' => 0, // Gallimard
            ],
            [
                'titre' => 'Les Fleurs du mal',
                'nbPages' => 248,
                'dateEdition' => new \DateTime('1857-01-01'),
                'nbExemplaires' => 8,
                'prix' => 7.90,
                'isbn' => '9782070419192',
                'auteur' => 0, // Victor Hugo (représentant la poésie)
                'categorie' => 3, // Poésie
                'editeur' => 0, // Gallimard
            ],
            [
                'titre' => 'Du côté de chez Swann',
                'nbPages' => 576,
                'dateEdition' => new \DateTime('1913-01-01'),
                'nbExemplaires' => 31,
                'prix' => 18.90,
                'isbn' => '9782070419193',
                'auteur' => 3, // Marcel Proust
                'categorie' => 0, // Roman
                'editeur' => 0, // Gallimard
            ],
        ];

        foreach ($livresData as $data) {
            $livre = new Livre();
            $livre->setTitre($data['titre']);
            $livre->setNbPages($data['nbPages']);
            $livre->setDateEdition($data['dateEdition']);
            $livre->setNbExemplaires($data['nbExemplaires']);
            $livre->setPrix($data['prix']);
            $livre->setIsbn($data['isbn']);
            $livre->setAuteur($auteurs[$data['auteur']]);
            $livre->setCategorie($categories[$data['categorie']]);
            $livre->setEditeur($editeurs[$data['editeur']]);
            $manager->persist($livre);
        }

        $manager->flush();
    }
}

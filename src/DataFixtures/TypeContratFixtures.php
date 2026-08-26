<?php

namespace App\DataFixtures;

use App\Entity\TypeContrat;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TypeContratFixtures extends Fixture
{
    public const REFERENCE_PREFIXE = 'type_contrat_';

    public function load(ObjectManager $manager): void
    {
        $libelles = [
            'Vacataire',
            'Titulaire',
            'Intervenant professionnel',
        ];

        foreach ($libelles as $libelle) {
            $typeContrat = new TypeContrat();
            $typeContrat->setLibelle($libelle);

            $manager->persist($typeContrat);

            $this->addReference(self::REFERENCE_PREFIXE.$libelle, $typeContrat);
        }

        $manager->flush();
    }
}

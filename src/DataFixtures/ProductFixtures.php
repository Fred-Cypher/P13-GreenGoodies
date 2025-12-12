<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $products = [
            [
                "name" => "Kit d'hygiène recyclable",
                "short_description" => "Pour une salle de bain éco-friendly",
                "full_description" => "Duplexque isdem diebus acciderat malum, quod et Theophilum insontem atrox interceperat casus, et Serenianus dignus exsecratione cunctorum, innoxius, modo non reclamante publico vigore, discessit.

Homines enim eruditos et sobrios ut infaustos et inutiles vitant, eo quoque accedente quod et nomenclatores adsueti haec et talia venditare, mercede accepta lucris quosdam et prandiis inserunt subditicios ignobiles et obscuros.

Quo cognito Constantius ultra mortalem modum exarsit ac nequo casu idem Gallus de futuris incertus agitare quaedam conducentia saluti suae per itinera conaretur, remoti sunt omnes de industria milites agentes in civitatibus perviis.",
                "price" => 24.99,
                "picture" => "recyclableHygieneKit.webp",
                "created_at" => new \DateTimeImmutable(),
                "updated_at" => new \DateTimeImmutable(),
            ],
            [
                "name" => "Shot Tropical",
                "short_description" => "Fruits frais, pressés à froid",
                "full_description" => "Duplexque isdem diebus acciderat malum, quod et Theophilum insontem atrox interceperat casus, et Serenianus dignus exsecratione cunctorum, innoxius, modo non reclamante publico vigore, discessit.

Homines enim eruditos et sobrios ut infaustos et inutiles vitant, eo quoque accedente quod et nomenclatores adsueti haec et talia venditare, mercede accepta lucris quosdam et prandiis inserunt subditicios ignobiles et obscuros.

Quo cognito Constantius ultra mortalem modum exarsit ac nequo casu idem Gallus de futuris incertus agitare quaedam conducentia saluti suae per itinera conaretur, remoti sunt omnes de industria milites agentes in civitatibus perviis.",
                "price" => 4.50,
                "picture" => "tropicalShot.webp",
                "created_at" => new \DateTimeImmutable(),
                "updated_at" => new \DateTimeImmutable(),
            ],
            [
                "name" => "Gourde en bois",
                "short_description" => "50 cl, bois d'olivier",
                "full_description" => "Duplexque isdem diebus acciderat malum, quod et Theophilum insontem atrox interceperat casus, et Serenianus dignus exsecratione cunctorum, innoxius, modo non reclamante publico vigore, discessit.

Homines enim eruditos et sobrios ut infaustos et inutiles vitant, eo quoque accedente quod et nomenclatores adsueti haec et talia venditare, mercede accepta lucris quosdam et prandiis inserunt subditicios ignobiles et obscuros.

Quo cognito Constantius ultra mortalem modum exarsit ac nequo casu idem Gallus de futuris incertus agitare quaedam conducentia saluti suae per itinera conaretur, remoti sunt omnes de industria milites agentes in civitatibus perviis.",
                "price" => 16.90,
                "picture" => "woodenGourde.webp",
                "created_at" => new \DateTimeImmutable(),
                "updated_at" => new \DateTimeImmutable(),
            ],
            [
                "name" => "Disques Démaquillants x3",
                "short_description" => "Solution efficace pour vous démaquiller en douceur",
                "full_description" => "Duplexque isdem diebus acciderat malum, quod et Theophilum insontem atrox interceperat casus, et Serenianus dignus exsecratione cunctorum, innoxius, modo non reclamante publico vigore, discessit.

Homines enim eruditos et sobrios ut infaustos et inutiles vitant, eo quoque accedente quod et nomenclatores adsueti haec et talia venditare, mercede accepta lucris quosdam et prandiis inserunt subditicios ignobiles et obscuros.

Quo cognito Constantius ultra mortalem modum exarsit ac nequo casu idem Gallus de futuris incertus agitare quaedam conducentia saluti suae per itinera conaretur, remoti sunt omnes de industria milites agentes in civitatibus perviis.",
                "price" => 19.90,
                "picture" => "makeupRemoverPads.webp",
                "created_at" => new \DateTimeImmutable(),
                "updated_at" => new \DateTimeImmutable(),
            ],
            [
                "name" => "Bougie Lavande & Patchouli",
                "short_description" => "Cire naturelle",
                "full_description" => "Duplexque isdem diebus acciderat malum, quod et Theophilum insontem atrox interceperat casus, et Serenianus dignus exsecratione cunctorum, innoxius, modo non reclamante publico vigore, discessit.

Homines enim eruditos et sobrios ut infaustos et inutiles vitant, eo quoque accedente quod et nomenclatores adsueti haec et talia venditare, mercede accepta lucris quosdam et prandiis inserunt subditicios ignobiles et obscuros.

Quo cognito Constantius ultra mortalem modum exarsit ac nequo casu idem Gallus de futuris incertus agitare quaedam conducentia saluti suae per itinera conaretur, remoti sunt omnes de industria milites agentes in civitatibus perviis.",
                "price" => 32,
                "picture" => "lavenderCandles.webp",
                "created_at" => new \DateTimeImmutable(),
                "updated_at" => new \DateTimeImmutable(),
            ],
            [
                "name" => "Brosse à dents",
                "short_description" => "Bois de hêtre rouge issu de forêts gérées durablement",
                "full_description" => "Duplexque isdem diebus acciderat malum, quod et Theophilum insontem atrox interceperat casus, et Serenianus dignus exsecratione cunctorum, innoxius, modo non reclamante publico vigore, discessit.

Homines enim eruditos et sobrios ut infaustos et inutiles vitant, eo quoque accedente quod et nomenclatores adsueti haec et talia venditare, mercede accepta lucris quosdam et prandiis inserunt subditicios ignobiles et obscuros.

Quo cognito Constantius ultra mortalem modum exarsit ac nequo casu idem Gallus de futuris incertus agitare quaedam conducentia saluti suae per itinera conaretur, remoti sunt omnes de industria milites agentes in civitatibus perviis.",
                "price" => 5.40,
                "picture" => "toothbrushes.webp",
                "created_at" => new \DateTimeImmutable(),
                "updated_at" => new \DateTimeImmutable(),
            ],
            [
                "name" => "Kit couverts en bois",
                "short_description" => "Revêtement Bio en olivier & sac de transport",
                "full_description" => "Duplexque isdem diebus acciderat malum, quod et Theophilum insontem atrox interceperat casus, et Serenianus dignus exsecratione cunctorum, innoxius, modo non reclamante publico vigore, discessit.

Homines enim eruditos et sobrios ut infaustos et inutiles vitant, eo quoque accedente quod et nomenclatores adsueti haec et talia venditare, mercede accepta lucris quosdam et prandiis inserunt subditicios ignobiles et obscuros.

Quo cognito Constantius ultra mortalem modum exarsit ac nequo casu idem Gallus de futuris incertus agitare quaedam conducentia saluti suae per itinera conaretur, remoti sunt omnes de industria milites agentes in civitatibus perviis.",
                "price" => 12.30,
                "picture" => "woodenCutlery.webp",
                "created_at" => new \DateTimeImmutable(),
                "updated_at" => new \DateTimeImmutable(),
            ],
            [
                "name" => "Nécessaire, déodorant Bio",
                "short_description" => "50 ml déodorant à l'eucalyptus",
                "full_description" => "Déodorant Nécessaire, une formule révolutionnaire composée exclusivement d'ingrédients naturels pour une protection efficace et bienfaisante.

Chaque flacon de 50 ml renferme le secret d'une fraîcheur longue durée, sans compromettre votre bien-être ni l'environnement. Conçu avec soin, ce déodorant allie le pouvoir antibactérien des extraits de plantes aux vertus apaisantes des huiles essentielles, assurant une sensation de confort toute la journée.

Grâce à sa formule non irritante et respectueuse de votre peau, Nécessaire offre une alternative saine aux déodorants conventionnels, tout en préservant l'équilibre naturel de votre corps.",
                "price" => 8.50,
                "picture" => "deodorant.webp",
                "created_at" => new \DateTimeImmutable(),
                "updated_at" => new \DateTimeImmutable(),
            ],
            [
                "name" => "Savon Bio",
                "short_description" => "Thé, Orange & Girofle",
                "full_description" => "Duplexque isdem diebus acciderat malum, quod et Theophilum insontem atrox interceperat casus, et Serenianus dignus exsecratione cunctorum, innoxius, modo non reclamante publico vigore, discessit.

Homines enim eruditos et sobrios ut infaustos et inutiles vitant, eo quoque accedente quod et nomenclatores adsueti haec et talia venditare, mercede accepta lucris quosdam et prandiis inserunt subditicios ignobiles et obscuros.

Quo cognito Constantius ultra mortalem modum exarsit ac nequo casu idem Gallus de futuris incertus agitare quaedam conducentia saluti suae per itinera conaretur, remoti sunt omnes de industria milites agentes in civitatibus perviis.",
                "price" => 18.90,
                "picture" => "soap.webp",
                "created_at" => new \DateTimeImmutable(),
                "updated_at" => new \DateTimeImmutable(),
            ]
        ];

        foreach ($products as $productData) {
            $product = new Product();
            $product->setName($productData["name"]);
            $product->setShortDescription($productData["short_description"]);
            $product->setFullDescription($productData["full_description"]);
            $product->setPrice($productData["price"]);
            $product->setPicture($productData["picture"]);
            $product->setCreatedAt(new \DateTimeImmutable());
            $product->setUpdatedAt(new \DateTimeImmutable());

            $manager->persist($product);
        }

        $manager->flush();
    }
}

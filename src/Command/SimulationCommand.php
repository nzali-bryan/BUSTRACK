<?php

namespace App\Command;

use App\Repository\AdministrateurRepository;
use App\Repository\BusRepository;
use App\Repository\TrajetsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:simulation', description: 'Simule le comportement des conducteurs pour la présentation')]
class SimulationCommand extends Command
{
    public function __construct(
        private BusRepository $busRepository,
        private AdministrateurRepository $administrateurRepository,
        private TrajetsRepository $trajetsRepository,
        private EntityManagerInterface $em
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('🚀 Simulation des conducteurs Bustrack...');
        $output->writeln('');

        // Récupérer tous les bus
        $buses = $this->busRepository->findAllBusNotNull();

        
        if (count($buses) === 0) {
            $output->writeln('❌ Aucun bus trouvé.');
            return Command::FAILURE;
        }

        // Récupérer un trajet existant
        $trajet = $this->trajetsRepository->findOneBy([]);
        if (!$trajet) {
            $output->writeln('❌ Aucun trajet trouvé.');
            return Command::FAILURE;
        }

        $compteur = [
            'En route' => 0,
            'Chargement' => 0,
            'Inactif' => 0,
            'Incident' => 0,
            'total' => 0
        ];

        foreach ($buses as $bus) {
            $compteur['total']++;
            
            // Récupérer l'ancien état
            $ancienEtat = $bus->getEtat();
            

            
            // Choisir un nouvel état aléatoire (pondéré pour avoir plus de "En route")
            $etats = ['En route', 'En route', 'En route', 'Chargement', 'Inactif'];
            $nouvelEtat = $etats[array_rand($etats)];
            $bus->setEtat($nouvelEtat);
            $compteur[$nouvelEtat]++;
            
            // Appliquer la logique métier (comme dans ton contrôleur conducteur)
            $etatBus = $bus->getEtat();
            
            if ($etatBus === 'En route' && $ancienEtat === 'En route') {
                // Bus déjà en route : maintenir le temps
                $vitesse = $bus->getVitesse() ?: 20;
                $distance = $trajet->getDistance() ?: 12;
                $tempsInitial = ($distance / $vitesse) * 60;
                $bus->setTempsInitial((int) round($tempsInitial));
                $bus->setStartTime(new \DateTimeImmutable());
                
                $output->writeln(sprintf('   🔄 %s : reste "En route" → temps initial = %d min', $bus->getNom(), (int) round($tempsInitial)));
                
            } elseif ($etatBus === 'Incident' && $ancienEtat === 'En route') {
                
                $tempsRestant = $bus->getTempsRestant();
                if ($tempsRestant) {
                    $bus->setTempsInitial((int) round($tempsRestant));
                }
                $output->writeln(sprintf('   ⚠️ %s : Incident → temps restant sauvegardé', $bus->getNom()));
                
            } elseif ($etatBus === 'En route' && $ancienEtat === 'Incident') {
                // Reprise après incident : reset startTime
                $bus->setStartTime(new \DateTimeImmutable());
                $output->writeln(sprintf('   🔄 %s : Reprise après incident → startTime mis à jour', $bus->getNom()));
                
            } else {
                // Autres cas (Chargement, Inactif)
                $bus->setTempsInitial(null);
                $output->writeln(sprintf('   📦 %s : %s → temps initial null', $bus->getNom(), $nouvelEtat));
            }
            
            // Définir la marge en fonction du temps initial
            $tempsInitial = $bus->getTempsInitial();
            if ($tempsInitial !== null) {
                if ($tempsInitial <= 10) {
                    $marge = 12;
                } elseif ($tempsInitial <= 20) {
                    $marge = 20;
                } elseif ($tempsInitial <= 30) {
                    $marge = 27;
                } else {
                    $marge = 33;
                }
                $bus->setMarge($marge);
                $output->writeln(sprintf('   📊 %s : marge = %d%%', $bus->getNom(), $marge));
            }
            
            $this->em->persist($bus);
        }

        $this->em->flush();

        // Affichage récapitulatif
        $output->writeln('');
        $output->writeln('✅ Simulation terminée !');
        $output->writeln('📊 Récapitulatif des états :');
        $output->writeln(sprintf('   🟢 En route   : %d bus', $compteur['En route']));
        $output->writeln(sprintf('   📦 Chargement : %d bus', $compteur['Chargement']));
        $output->writeln(sprintf('   ⚠️ Incident   : %d bus', $compteur['Incident']));
        $output->writeln(sprintf('   📋 Total      : %d bus', $compteur['total']));
        $output->writeln('');
        $output->writeln('👉 Ouvre maintenant ton interface utilisateur pour voir le résultat.');

        return Command::SUCCESS;
    }
}
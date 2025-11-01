<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create all roles
        $administrateur = Role::create(['name' => 'Administrateur']);
        $econome = Role::create(['name' => 'Économe']);
        $magasinier = Role::create(['name' => 'Magasinier']);
        $formateur = Role::create(['name' => 'Formateur']);
        
        // Add the additional roles from your data
        $utilisateur = Role::create(['name' => 'Utilisateur']);
        $gestionnaire = Role::create(['name' => 'Gestionnaire']);

        // Administrateur has all permissions
        $administrateur->givePermissionTo([
            'Products',
            'Products-ajoute',
            'Products-modifier',
            'Products-supprimer',

            'Taxes',
            'Taxes-ajoute',
            'Taxes-modifier',
            'Taxes-supprimer',

            'Fournisseurs',
            'Fournisseurs-ajoute',
            'Fournisseurs-modifier',
            'Fournisseurs-supprimer',

            // 'Formateurs',
            // 'Formateurs-ajoute',
            // 'Formateurs-modifier',
            // 'Formateurs-supprimer',

            'Categories',
            'Categories-ajoute',
            'Categories-modifier',
            'Categories-supprimer',

            'Local',
            'Local-ajoute',
            'Local-modifier',
            'Local-supprimer',

            'Rayon',
            'Rayon-ajoute',
            'Rayon-modifier',
            'Rayon-supprimer',

            'Famille',
            'Famille-ajoute',
            'Famille-modifier',
            'Famille-supprimer',

            'Achat',
            'Achat-ajoute',
            'Achat-modifier',
            'Achat-supprimer',
            
            'Commande',
            'Commande-ajoute',
            'Commande-modifier',
            'Commande-supprimer',

            'Historique',
            'Historique-Export',
            'Historique-montrer',
            
            'Unité',
            'Unité-ajoute',
            'Unité-modifier',
            'Unité-supprimer',

            'utilisateur',
            'utilisateur-ajoute',
            'utilisateur-modifier',
            'utilisateur-supprimer',

            'rôles',
            'rôles-ajoute',
            'rôles-voir',
            'rôles-modifier',
            'rôles-supprimer',
            
            'Transfer-ajoute',
            'Transfer-modifier',
            'Transfer-supprimer',
            'Transfer',
            'retour',
            'retour-ajouter',
            'retour-modifier',
            'retour-supprimer',
            'Stock',
            'Inventaire',
            'Voir-Consommation',
            'Voir-Consommation-Complète',
            'Voir-Rapport-Mensuel-Consommation',
            'Voir-Stock-Demandeur',
            'Pertes-ajouter',
            'Pertes-modifier',
            'Pertes-supprimer',
            'Pertes-valider',
            'Pertes-voir',
            'Plats',
            'Plats-liste',        
            'Plats-ajoute',       
            'Plats-modifier',     
            'Plats-supprimer',     
        ]);

        // Économe permissions
        $econome->givePermissionTo([
            'Products',
            'Products-ajoute',
            'Products-modifier',
            
            'Taxes',
            'Taxes-ajoute',
            'Taxes-modifier',
            
            'Fournisseurs',
            'Fournisseurs-ajoute',
            'Fournisseurs-modifier',
            
            'Categories',
            'Categories-ajoute',
            'Categories-modifier',
            
            'Local',
            'Local-ajoute',
            'Local-modifier',
            
            'Rayon',
            'Rayon-ajoute',
            'Rayon-modifier',
            
            'Famille',
            'Famille-ajoute',
            'Famille-modifier',
            
            'Achat',
            'Achat-ajoute',
            'Achat-modifier',
            
            'Commande',
            'Commande-ajoute',
            'Commande-modifier',
            
            'Historique',
            'Historique-Export',
            'Historique-montrer',
            
            'Unité',
            'Unité-ajoute',
            'Unité-modifier',

            'Transfer-ajoute',
            'Transfer-modifier',
            'Transfer-supprimer',
            'Transfer',
            'retour',
            'retour-ajouter',
            'retour-modifier',
            'retour-supprimer',
            'Inventaire',
            'Voir-Consommation',
            'Voir-Consommation-Complète',
            'Voir-Rapport-Mensuel-Consommation',
            'Voir-Stock-Demandeur',
        ]);

        // Magasinier permissions
        $magasinier->givePermissionTo([
            'Products',
            
            'Fournisseurs',
            
            'Categories',
            
            'Local',
            
            'Rayon',
            
            'Famille',
            
            'Achat',
            
            'Commande',
            'Commande-ajoute',
            'Commande-modifier',
            
            'Historique',
            'Historique-montrer',
            
            'Unité',
        ]);

        // Formateur permissions
        $formateur->givePermissionTo([
            'Products',
            
            'Commande',
            'Commande-ajoute',
            
            'Historique',
            'Historique-montrer',
        ]);

        // Gestionnaire permissions (similar to Économe but with less privileges)
        $gestionnaire->givePermissionTo([
            'Products',
            
            'Categories',
            
            'Local',
            
            'Rayon',
            
            'Famille',
            
            'Commande',
            'Commande-ajoute',
            
            'Historique',
            'Historique-montrer',
            
            'Transfer',
        ]);

        // Utilisateur permissions (basic view permissions)
        $utilisateur->givePermissionTo([
            'Products',
            
            'Commande',
            'Commande-ajoute',
            
            'Historique',
            'Historique-montrer',
        ]);
    }
}
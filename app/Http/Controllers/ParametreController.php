<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

class ParametreController extends Controller
{
    // Afficher la page paramètres
    public function index()
    {
        return view('parametres.index');
    }

    // Sauvegarder la base de données
    public function sauvegarder()
    {
        try {
            $database = config('database.connections.mysql.database');
            $username = config('database.connections.mysql.username');
            $password = config('database.connections.mysql.password');
            $host     = config('database.connections.mysql.host');

            $filename = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
            $path     = storage_path('app/backups/' . $filename);

            // Créer le dossier si inexistant
            if (!file_exists(storage_path('app/backups'))) {
                mkdir(storage_path('app/backups'), 0755, true);
            }

            // Commande mysqldump
            $command = "mysqldump --user={$username} --password={$password} --host={$host} {$database} > {$path}";
            exec($command);

            return redirect()->route('parametres.index')
                ->with('success', "Sauvegarde effectuée avec succès ! Fichier : {$filename}");

        } catch (\Exception $e) {
            return redirect()->route('parametres.index')
                ->with('error', 'Erreur lors de la sauvegarde : ' . $e->getMessage());
        }
    }

    // Restaurer la base de données
    public function restaurer(Request $request)
    {
        $request->validate([
            'fichier_backup' => 'required|file|mimes:sql',
        ]);

        try {
            $database = config('database.connections.mysql.database');
            $username = config('database.connections.mysql.username');
            $password = config('database.connections.mysql.password');
            $host     = config('database.connections.mysql.host');

            $file = $request->file('fichier_backup');
            $path = $file->getPathname();

            // Commande mysql restore
            $command = "mysql --user={$username} --password={$password} --host={$host} {$database} < {$path}";
            exec($command);

            return redirect()->route('parametres.index')
                ->with('success', 'Base de données restaurée avec succès !');

        } catch (\Exception $e) {
            return redirect()->route('parametres.index')
                ->with('error', 'Erreur lors de la restauration : ' . $e->getMessage());
        }
    }

    // Liste des sauvegardes disponibles
    public function listeSauvegardes()
    {
        $backupPath = storage_path('app/backups');
        $fichiers   = [];

        if (file_exists($backupPath)) {
            $fichiers = array_diff(scandir($backupPath), ['.', '..']);
            rsort($fichiers);
        }

        return $fichiers;
    }
}
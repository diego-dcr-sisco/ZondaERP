<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\AppearanceSetting;
use App\Models\Tenant;
class ConfigurationController extends Controller
{
    public function index() {
        return view('configuration.index');
    }

    public function appearance() {
        // Obtener la configuración actual o crear una por defecto
        $appearance = AppearanceSetting::first();
        
        
        return view('configuration.system.appearance', compact('appearance'));
    }

    public function updateAppearance(Request $request) {
        $request->validate([
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'watermark' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'primary_color' => 'required|string|max:7',
            'secondary_color' => 'required|string|max:7',
        ]);

        // Obtener o crear la configuración
        $appearance = AppearanceSetting::first();
        
        if (!$appearance) {
            $appearance = new AppearanceSetting();
        }

        // Manejar la carga del logo si se proporciona
        if ($request->hasFile('logo')) {
            $logoPath = $this->storeTenantFile($request->file('logo'), 'logo');
            
            
            // Guardar la ruta en la base de datos
            $appearance->logo_path = $logoPath;
        }

        // Manejar la carga de watermark
        if ($request->hasFile('watermark')) {
            $watermarkPath = $this->storeTenantFile($request->file('watermark'), 'watermark');
            
            // Guardar la ruta en la base de datos
            $appearance->watermark_path = $watermarkPath;
        }

        // Actualizar los colores
        $appearance->primary_color = $request->primary_color;
        $appearance->secondary_color = $request->secondary_color;
        $watermarkOpacity = $request->input('watermark_opacity', 10); 
        $appearance->watermark_opacity = $watermarkOpacity / 100; //Convertir valor a decimal
       
        
        $appearance->save();


        return redirect()->route('config.appearance')->with('success', 'Apariencia actualizada correctamente.');
    }

    private function storeTenantFile($file, $type)
    {
        $extension = $file->getClientOriginalExtension();
        $filename = "{$type}.{$extension}";
        $directory = 'images';
        
        // Eliminar archivos anteriores con el mismo nombre base
        $this->deletePreviousFiles($type, $directory, 'public');
        
        // Guardar el nuevo archivo
        return $file->storeAs($directory, $filename, 'public');
    }

    private function deletePreviousFiles($filenameBase, $directory, $disk = 'public')
    {
        $pattern = $filenameBase . '.*';
        $matchingFiles = Storage::disk($disk)->files($directory);
        
        foreach ($matchingFiles as $file) {
            $currentFilename = pathinfo($file, PATHINFO_FILENAME);
            if ($currentFilename === $filenameBase) {
                Storage::disk($disk)->delete($file);
            }
        }
    }

}
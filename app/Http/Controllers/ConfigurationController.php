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
        
        if (!$appearance) {
            $appearance = new AppearanceSetting();
        }
        
        return view('configuration.system.appearance', compact('appearance'));
    }

    public function updateAppearance(Request $request) {
        $request->validate([
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
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

        // Actualizar los colores
        $appearance->primary_color = $request->primary_color;
        $appearance->secondary_color = $request->secondary_color;
       
        
        $appearance->save();


        return redirect()->route('config.appearance')->with('success', 'Apariencia actualizada correctamente.');
    }

    private function storeTenantFile($file, $type)
    {
        $user = auth()->user();
        $tenant = Tenant::find($user->tenant_id);
        $extension = $file->getClientOriginalExtension();
        $filename = "{$type}.{$extension}";
        
        // Guardar directamente en la raíz del disco
        $filePath = $file->storeAs('', $filename, 'public');
        
        return $filePath;
    }

}
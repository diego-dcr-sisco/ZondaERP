<?php

namespace App\Http\Controllers;

use App\Models\CustomerZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ZoneController extends Controller
{
    protected $zones = [
        'San Luis',
        'Aguascalientes',
        'Rioverde',
        'Cd. Valles',
        'Tamazunchale',
        'Jalisco',
        'Tecoman',
        'Culiacan',
        'Durango',
        'Guanajuato',
        'Monterrey',
        'Queretaro',
        'Veracruz',
        'Yucatan',
        'Matamoros'
    ];
    
    public $navigation = [
        'Almacenes' => [
            'route' => '/stock',
            'permission' => null
        ],
        'Lotes' => [
            'route' => '/lot/index',
            'permission' => null
        ],
        'Productos' => [
            'route' => '/products',
            'permission' => null
        ],
        'Movimientos' => [
            'route' => '/stock/movements',
            'permission' => null
        ],
        'Zonas' => [
            'route' => '/customer-zones',
            'permission' => null
        ],
        'Consumos' => [
            'route' => '/consumptions',
            'permision' => null
        ],
        /*'Pedidos' => [
            'route' => '/consumptions',
            'permission' => null
        ],
        'Productos en ordenes' => [
            'route' => '/stock/orders-products',
            'permission' => null
        ],
        'Estadisticas' => [
            'route' => 'stock/analytics',
            'permission' => null
        ],
        'Compras' => [
            'route' => '/purchase-requisition/purchases',
            'permission' => null
        ]*/
    ];

    
    /**
     * Get unique zone names for dropdown
     */
    public function __invoke(Request $request)
    {
        // Get distinct zone names
        $zones = CustomerZone::select('zone')
            ->distinct()
            ->orderBy('zone')
            ->pluck('zone')
            ->toArray();
        
        // If there are no zones in the database, provide some default options
        if (empty($zones)) {
            $zones = [
                'San Luis',
                'Aguascalientes',
                'Rioverde',
                'Cd. Valles',
                'Tamazunchale',
                'Jalisco',
                'Tecoman',
                'Culiacan',
                'Durango',
                'Guanajuato',
                'Monterrey',
                'Queretaro',
                'Veracruz',
                'Yucatan',
                'Matamoros'
            ];
        }
        
        return response()->json([
            'success' => true,
            'zones' => $zones
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use Afip;
use Illuminate\Http\Request;

//use Gonzakpo\AfipBundle\Controller\AfipController as AfipBundleController;

class AfipController extends Controller
{
    private $afipws;

    public function __construct()
    {
        $this->afipws = new Afip([
            'CUIT' => 20373561690,
            'res_folder' => base_path('app/Certs/'),
            'ta_folder' => base_path('app/Certs/'),
            //'cert' => 'miempresa.crt',
            /*'cert' => 'miempresacert.pem',
            'key' => 'nosotros.key',*/
            'cert' => 'miempresaprod.crt',
            'key' => 'nosotrosprod.key',
            'production' => true
            //'passphrase' => 'ejemplocert'
        ], base_path());
    }

    /**
     * Obtenemos los tipos de comprobantes de AFIP y filtramos los que queremos mostrar
     *
     * @return \Illuminate\Support\Collection
     */
    public function getComprobantesDisponibles()
    {
        $afip = $this->afipws;

        // Obtenemos todos los comprobantes de AFIP
        $tipos_comprobantes = collect($afip->ElectronicBilling->GetVoucherTypes());

        // Definimos que comprobantes vamos a aceptar
        $comprobantes_validos = [
            'Factura A',
            'Nota de Débito A',
            'Nota de Crédito A',
            'Factura B',
            'Nota de Débito B',
            'Nota de Crédito B',
            'Factura C',
            'Nota de Débito C',
            'Nota de Crédito C'
        ];

        $tipos_comprobantes =
            $tipos_comprobantes->filter(function ($item) use ($comprobantes_validos) {
                return in_array($item->Desc, $comprobantes_validos);
            });

        return $tipos_comprobantes;
    }

    /**
     *
     * Obtenemos los tipos de documentos de AFIP y filtramos los que queremos mostrar
     *
     * @return \Illuminate\Support\Collection
     */
    public function getTiposDocumentos()
    {
        $afip = $this->afipws;

        // Obtenemos todos los tipos de documentos de AFIP
        $tipos_documentos = collect($afip->ElectronicBilling->GetDocumentTypes());

        // Definimos que tipos de documentos vamos a aceptar
        $tipos_documentos_validos = [
            'CUIT',
            'CUIL',
            'DNI'
        ];

        $tipos_documentos =
            $tipos_documentos->filter(function ($item) use ($tipos_documentos_validos) {
                return in_array($item->Desc, $tipos_documentos_validos);
            });

        return $tipos_documentos;
    }

    /**
     *
     * Obtenemos los tipos de conceptos de AFIP y filtramos los que queremos mostrar
     *
     * @return \Illuminate\Support\Collection
     */
    public function getTiposConceptos()
    {
        // Obtenemos todos los tipos de documentos de AFIP
        $tipos_conceptos = collect($this->afipws->ElectronicBilling->GetConceptTypes());

        return $tipos_conceptos;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getInfoContribuyente(Request $request)
    {
        $afip = $this->afipws;

        $nro_documento = (float) $request->nro_documento;


        // Buscamos el contribuyente en los WS de AFIP
        $contribuyente = $afip->RegisterScopeFive->GetTaxpayerDetails($nro_documento);

        return response()->json($contribuyente);
    }

    public function generarFactura()
    {
        $data = array(
            'CantReg' => 1, // Cantidad de comprobantes a registrar
            'PtoVta' => 1, // Punto de venta
            'CbteTipo' => 6, // Tipo de comprobante (ver tipos disponibles)
            'Concepto' => 1, // Concepto del Comprobante: (1)Productos, (2)Servicios, (3)Productos y Servicios
            'DocTipo' => 80, // Tipo de documento del comprador (ver tipos disponibles)
            'DocNro' => 20111111112, // Numero de documento del comprador
            'CbteDesde' => 2, // Numero de comprobante o numero del primer comprobante en caso de ser mas de uno
            'CbteHasta' => 2, // Numero de comprobante o numero del ultimo comprobante en caso de ser mas de uno
            'CbteFch' => intval(date('Ymd')), // (Opcional) Fecha del comprobante (yyyymmdd) o fecha actual si es nulo
            'ImpTotal' => 150, // Importe total del comprobante
            'ImpTotConc' => 150, // Importe neto no gravado
            'ImpNeto' => 0, // Importe neto gravado
            'ImpOpEx' => 0, // Importe exento de IVA
            'ImpIVA' => 0, //Importe total de IVA
            'ImpTrib' => 0, //Importe total de tributos
            'FchServDesde' => NULL, // (Opcional) Fecha de inicio del servicio (yyyymmdd), obligatorio para Concepto 2 y 3
            'FchServHasta' => NULL, // (Opcional) Fecha de fin del servicio (yyyymmdd), obligatorio para Concepto 2 y 3
            'FchVtoPago' => NULL, // (Opcional) Fecha de vencimiento del servicio (yyyymmdd), obligatorio para Concepto 2 y 3
            'MonId' => 'PES', //Tipo de moneda usada en el comprobante (ver tipos disponibles)('PES' para pesos argentinos)
            'MonCotiz' => 1, // Cotización de la moneda usada (1 para pesos argentinos)
            /*'CbtesAsoc' 	=> array( // (Opcional) Comprobantes asociados
                array(
                    'Tipo' 		=> 6, // Tipo de comprobante (ver tipos disponibles)
                    'PtoVta' 	=> 1, // Punto de venta
                    'Nro' 		=> 1, // Numero de comprobante
                    'Cuit' 		=> 20111111112 // (Opcional) Cuit del emisor del comprobante
                )
            ),
            'Tributos' 		=> array( // (Opcional) Tributos asociados al comprobante
                array(
                    'Id' 		=>  99, // Id del tipo de tributo (ver tipos disponibles)
                    'Desc' 		=> 'Ingresos Brutos', // (Opcional) Descripcion
                    'BaseImp' 	=> 150, // Base imponible para el tributo
                    'Alic' 		=> 5.2, // Alícuota
                    'Importe' 	=> 7.8 // Importe del tributo
                )
            ),
            'Iva' 			=> array( // (Opcional) Alícuotas asociadas al comprobante
                array(
                    'Id' 		=> 4, // Id del tipo de IVA (ver tipos disponibles)
                    'BaseImp' 	=> 100, // Base imponible
                    'Importe' 	=> 26.25 // Importe
                )
            ),
            'Opcionales' 	=> array( // (Opcional) Campos auxiliares
                array(
                    'Id' 		=> 17, // Codigo de tipo de opcion (ver tipos disponibles)
                    'Valor' 	=> 2 // Valor
                )
            ),
            'Compradores' 	=> array( // (Opcional) Detalles de los clientes del comprobante
                array(
                    'DocTipo' 		=> 80, // Tipo de documento (ver tipos disponibles)
                    'DocNro' 		=> 20111111112, // Numero de documento
                    'Porcentaje' 	=> 100 // Porcentaje de titularidad del comprador
                )
            )*/
        );

        try {
            $afip = $this->afipws;

            //dd($afip->ElectronicBilling->GetLastVoucher(1, 6));

            dd($afip->ElectronicBilling->CreateNextVoucher($data, true));

        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    public function getVoucher($voucher)
    {
        $afip = $this->afipws->getWS();

        dd($afip->ElectronicBilling->GetVoucherInfo($voucher, 1, 6));
    }

    public function cargarDatosParaFacturar()
    {
        $datos['tipos_comprobantes'] = $this->getComprobantesDisponibles();

        return $datos;
    }
}

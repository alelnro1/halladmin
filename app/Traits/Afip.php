<?php

namespace App\Traits;

trait DatosParaFacturasAfip
{
    /**
     * Se solicitó una Factura A, puede recibirla solo un responsable inscripto
     *
     * @param $datos_afip
     * @return stdClass
     */
    public function getDatosDeContribuyenteParaFacturaA($datos_afip)
    {
        $contribuyente = new \stdClass();

        // Puede solo emitir a otro responsable inscripto
        if (!isset($datos_afip->datosRegimenGeneral)) {
            $contribuyente->error = true;
            $contribuyente->mensaje = "El contribuyente no es un responsable inscripto.";
        } else {
            $contribuyente->RazonSocial = $this->getRazonSocialResponsableInscripto($datos_afip);
            $contribuyente->DomicilioFiscal = $this->getDomicilioFiscal($datos_afip);
            $contribuyente->TipoContribuyente = "Responsable Inscripto en IVA";
        }

        return $contribuyente;
    }

    /**
     * Se solicitó una Factura B, puede recibirla cualquiera
     * Armamos los datos
     *
     * @param $datos_afip
     * @return stdClass
     */
    public function getDatosDeContribuyenteParaFacturaBoC($datos_afip)
    {
        $contribuyente = new \stdClass();

        if (isset($datos_afip->datosRegimenGeneral)) {
            // Es responsable inscripto
            $contribuyente->RazonSocial = $this->getRazonSocialResponsableInscripto($datos_afip);
            $contribuyente->TipoContribuyente = "Responsable Inscripto en IVA";

        } else if (isset($datos_afip->datosMonotributo)) {
            // Es monotributista
            $contribuyente->RazonSocial = $this->getRazonSocialMonotributista($datos_afip);
            $contribuyente->TipoContribuyente = "Monotributista";

        } else {
            // Es Consumidor Final
            $contribuyente->RazonSocial = $this->getRazonSocialConsumidorFinal($datos_afip);
            $contribuyente->TipoContribuyente = "Consumidor Final";
        }

        return $contribuyente;
    }

    private function getRazonSocialResponsableInscripto($contribuyente)
    {
        return $contribuyente->datosGenerales->razonSocial;
    }

    /**
     * Obtenemos la razon social de un monotributista
     *
     * @param $contribuyente
     * @return string
     */
    private function getRazonSocialMonotributista($contribuyente)
    {
        // Inicializo la razon social
        $razon_social = "";

        // Si hay un nombre lo agrego
        if (isset($contribuyente->datosGenerales->nombre)) {
            $razon_social .= $contribuyente->datosGenerales->nombre;
        }

        if (isset($contribuyente->errorConstancia->apellido)) {
            $razon_social .= $contribuyente->datosGenerales->apellido;
        }

        return $razon_social;
    }

    /**
     * Obtenemos la razon social de un consumidor final
     *
     * @param $contribuyente
     * @return string
     */
    private function getRazonSocialConsumidorFinal($contribuyente)
    {
        // Inicializo la razon social
        $razon_social = "";

        // Si hay un nombre lo agrego
        if (isset($contribuyente->errorConstancia->nombre)) {
            $razon_social .= $contribuyente->errorConstancia->nombre;
        }

        if (isset($contribuyente->errorConstancia->apellido)) {
            $razon_social .= $contribuyente->errorConstancia->apellido;
        }

        return $razon_social;
    }

    /**
     * Obtenemos el domicilio fiscal de un contribuyente
     *
     * @param $contribuyente
     * @return string
     */
    private function getDomicilioFiscal($contribuyente)
    {
        // Inicializo el domicilio fiscal
        $domicilio_fiscal = "";

        if (isset($contribuyente->datosGenerales->domicilioFiscal->direccion)) {
            $domicilio_fiscal .= $contribuyente->datosGenerales->domicilioFiscal->direccion;
        }

        if (isset($contribuyente->datosGenerales->domicilioFiscal->descripcionProvincia)) {
            $domicilio_fiscal .= $contribuyente->datosGenerales->domicilioFiscal->descripcionProvincia;
        }

        return $domicilio_fiscal;
    }
}
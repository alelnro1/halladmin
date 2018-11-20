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

    private function getRazonSocialMonotributista($contribuyente)
    {
        return $contribuyente->datosGenerales->nombre . " " . $contribuyente->datosGenerales->apellido;
    }

    private function getRazonSocialConsumidorFinal($contribuyente)
    {
        return $contribuyente->errorConstancia->nombre . " " . $contribuyente->errorConstancia->apellido;
    }

    private function getDomicilioFiscal($contribuyente)
    {
        return $contribuyente->datosGenerales->domicilioFiscal->direccion . ", " .
            $contribuyente->datosGenerales->domicilioFiscal->descripcionProvincia;
    }
}
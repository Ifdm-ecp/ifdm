<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class DesagregacionRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'radio_pozo' => 'required|numeric',
            'tipo_roca' => 'required|in:poco consolidada,consolidada,microfracturada',
            'presion_yacimiento' => 'required|numeric',
            'radio_drenaje_yac' => 'required|numeric',
            'tasa_flujo' => 'required|numeric|',
            'presion_fondo' => 'required|numeric|min:0',
            'profundidad_medida_pozo' => 'required|numeric|',
            'espesor_canoneado' => 'required|numeric|',
            'profundidad_penetracion_canones' => 'required|numeric|',
            'fase' => 'required|numeric|in:0,45,60,90,120,360',
            'radio_perforado' => 'required|numeric|',
            'forma_area_drenaje' => 'required|integer|min:1|max:16',
            'caudal_produccion_gas' => 'required|numeric|',
            'permeabilidad_abs_ini' => 'required|numeric|',
            'relacion_perm_horiz_vert' => 'required|numeric|',
            'viscosidad_aceite' => 'required|numeric|',
            'viscosidad_gas' => 'required|numeric|',
            'gravedad_especifica_gas' => 'required|numeric|',
            'factor_volumetrico_aceite' => 'required|numeric|',
            'gradiente_esfuerzo_horizontal_minimo' => 'required|numeric|',
            'gradiente_esfuerzo_horizontal_maximo' => 'required|numeric|',
            'gradiente_esfuerzo_vertical' => 'required|numeric|',
            'profundidad_real_formacion' => 'required|numeric|',
            'espesor_formacion_productora' => 'required|numeric|',
            'dano_total_pozo' => 'required|numeric',
        ];
    }

    public function messages()
    {
        return [
            'radio_pozo.required' => 'Well Radius is required.',
            'tipo_roca.required' => 'Rock Type is required.',
            'presion_yacimiento.required' => 'Reservoir Pressure is required.',
            'radio_drenaje_yac.required' => 'Reservoir Drainage Radius is required.',
            'tasa_flujo.required' => 'Production Rate is required.',
            'presion_fondo.required' => 'Bottomwhole Flowing Pressure is required.',
            'profundidad_medida_pozo.required' => 'Average Well Depth is required.',
            'espesor_canoneado.required' => 'Perforating Thickness is required.',
            'profundidad_penetracion_canones.required' => 'Perforating Penetretaion Depth is required.',
            'fase.required' => 'Phase is required.',
            'radio_perforado.required' => 'Perforated Radius is required.',
            'forma_area_drenaje.required' => 'Drainage area shape is required.',
            'caudal_produccion_gas.required' => 'Gas Production Rate is required.',
            'permeabilidad_abs_ini.required' => 'Absolute Permeability is required.',
            'relacion_perm_horiz_vert.required' => 'Horizontal Vertial Permeability Ratio is required.',
            'viscosidad_aceite.required' => 'Oil Viscosity is required.',
            'viscosidad_gas.required' => 'Gas Viscosity is required.',
            'gravedad_especifica_gas.required' => 'Gas Specific Gravity is required.',
            'factor_volumetrico_aceite.required' => 'Oil Volumetric Factor is required.',
            'gradiente_esfuerzo_horizontal_minimo.required' => 'Minimum Horizontal Stress Gradient is required.',
            'gradiente_esfuerzo_horizontal_maximo.required' => 'Maximum Horizontal Stress Gradient is required.',
            'gradiente_esfuerzo_vertical.required' => 'Vertical Stress Gradient is required.',
            'profundidad_real_formacion.required' => 'True Vertical Depth is required.',
            'espesor_formacion_productora.required' => 'Producing Formation Thickness is required.',
            'dano_total_pozo.required' => 'Total Well Damage is required.',

            'radio_pozo.numeric' => 'Well Radius must be numeric.',
            'tipo_roca.numeric' => 'Rock Type must be numeric.',
            'presion_yacimiento.numeric' => 'Reservoir Pressure must be numeric.',
            'radio_drenaje_yac.numeric' => 'Reservoir Drainage Radius must be numeric.',
            'tasa_flujo.numeric' => 'Production Rate must be numeric.',
            'presion_fondo.numeric' => 'Bottomwhole Flowing Pressure must be numeric.',
            'profundidad_medida_pozo.numeric' => 'Average Well Depth must be numeric.',
            'espesor_canoneado.numeric' => 'Perforating Thickness must be numeric.',
            'profundidad_penetracion_canones.numeric' => 'Perforating Penetretaion Depth must be numeric.',
            'fase.numeric' => 'Phase must be numeric.',
            'radio_perforado.numeric' => 'Perforated Radius must be numeric.',
            'forma_area_drenaje.numeric' => 'Drainage area shape must be numeric.',
            'caudal_produccion_gas.numeric' => 'Gas Production Rate must be numeric.',
            'permeabilidad_abs_ini.numeric' => 'Absolute Permeability must be numeric.',
            'relacion_perm_horiz_vert.numeric' => 'Horizontal Vertial Permeability Ratio must be numeric.',
            'viscosidad_aceite.numeric' => 'Oil Viscosity must be numeric.',
            'viscosidad_gas.numeric' => 'Gas Viscosity must be numeric.',
            'gravedad_especifica_gas.numeric' => 'Gas Specific Gravity must be numeric.',
            'factor_volumetrico_aceite.numeric' => 'Oil Volumetric Factor must be numeric.',
            'gradiente_esfuerzo_horizontal_minimo.numeric' => 'Minimum Horizontal Stress Gradient must be numeric.',
            'gradiente_esfuerzo_horizontal_maximo.numeric' => 'Maximum Horizontal Stress Gradient must be numeric.',
            'gradiente_esfuerzo_vertical.numeric' => 'Vertical Stress Gradient must be numeric.',
            'profundidad_real_formacion.numeric' => 'True Vertical Depth must be numeric.',
            'espesor_formacion_productora.numeric' => 'Producing Formation Thickness must be numeric.',
            'dano_total_pozo.numeric' => 'Total Well Damage must be numeric.',
        ];
        
    }
}

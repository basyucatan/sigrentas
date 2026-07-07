<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link href="{{ public_path('css/cssPDF.css') }}" rel="stylesheet">
</head>
<body>
@php
    use Luecano\NumeroALetras\NumeroALetras;
    function relleno($cantidad = 10, $caracter = '-')
    {
        return str_repeat($caracter . ' ', $cantidad);
    }
    function evaluar($valor, $nombreCampo, $resaltar = true, $formato = 'texto')
    {
        if (!$valor && $valor !== 0) {
            return '<span class="warning">FALTA ' . e(mb_strtoupper($nombreCampo)) . '</span>';
        }
        switch ($formato) {
            case 'dinero':
                $convertidor = new NumeroALetras();
                $texto = $convertidor->toMoney($valor, 2, 'PESOS', 'CENTAVOS');
                break;
            case 'numero':
                static $convertidor;
                $convertidor ??= new NumeroALetras();
                $texto = $convertidor->toWords($valor);
                break;
            case 'fecha':
                $texto = Util::formatFecha($valor, 'Texto');
                break;
            case 'dia':
                static $convertidor;
                $convertidor ??= new NumeroALetras();
                $texto = $convertidor->toWords(\Carbon\Carbon::parse($valor)->day);
                break;
            default:
                $texto = $valor;
                break;
        }
        $texto = mb_strtoupper($texto);
        if ($resaltar) {
            return '<span class="resaltar">' . e($texto) . '</span>';
        }
        return e($texto);
    }
@endphp
    <div class="parrafo">
        <span class="resaltar">CONVENIO TRANSACCIONAL DE DESOCUPACIÓN Y ENTREGA</span>
        de un predio urbano ubicado en esta Ciudad, que celebran: 
        {!! evaluar($contrato->propietario?->propietario, 'propietario') !!}
        como “LA PARTE PROPIETARIA” y por otro lado,
        <span class="resaltar2">
        {!! evaluar($contrato->inquilino?->inquilino, 'inquilino') !!}
        </span>
        como “LA PARTE OCUPANTE”, lo que llevan a cabo al tenor de las siguientes:
        <span>{{ relleno(32) }}</span>
    </div>
    <div class="parrafo">
        <span>{{ relleno(25) }}</span> C L Á U S U L A S 
        <span>{{ relleno(25) }}</span>
    </div>
    <div class="parrafo">
        <span class="resaltar">PRIMERA</span>.- Declara 
        {!! evaluar($contrato->propietario?->propietario, 'propietario') !!}, 
        que el predio en cuestión es de su legítima propiedad, en pleno dominio y posesión, y que se ubica en
        {!! evaluar($contrato->cuarto?->casa?->direccion, 'direccion') !!}
        la Calle sesenta y cinco letra “B” número quinientos diecinueve de la localidad y municipio 
        de Mérida, Yucatán, descrito de la manera siguiente: 
        {!! evaluar($contrato->cuarto?->casa?->adicionales['descripcion'], 'descripcion', false) !!}
    </div>
    <div class="parrafo">
        <span class="resaltar">SEGUNDA.- "LA  PARTE PROPIETARIA”</span>, manifiesta que en el bien inmueble descrito 
        y deslindado en el párrafo anterior, se encuentra físicamente dividido en varios departamentos 
        y el departamento objeto del presente convenio, se denomina convencionalmente como
        <span class="resaltar2">
            "DEPARTAMENTO {!! evaluar($contrato->cuarto?->cuarto, '# DEPTO', false, 'numero') !!}". 
        </span>
        <span>{{ relleno(40) }}</span>
    </div>
    <div class="parrafo">
        SEGUNDA.- “LA PARTE OCUPANTE” reconoce y declara expresamente que ocupa el inmueble descrito y 
        deslindado en el apartado que antecede sin título jurídico alguno, por lo que se obliga y 
        compromete a restituirlo y entregarlo totalmente desocupado a favor de “LA PARTE PROPIETARIA” 
        dentro de un plazo a partir del día
        <span class="resaltar2">
            {!! evaluar($contrato->fechaIni, 'fecha inicial', false, 'fecha') !!}, 
        </span>
        el cual concluirá, sin necesidad de requerimiento previo, el día 
        <span class="resaltar2">
            {!! evaluar($contrato->fechaFin, 'fecha final', false, 'fecha') !!}, 
        </span>
        pudiendo dicho plazo ser prorrogado únicamente mediante acuerdo expreso y por escrito entre las partes. 
        <span>{{ relleno(40) }}</span> 
    </div>
    <div class="parrafo">
        Durante la vigencia del presente convenio, “LA PARTE OCUPANTE” se obliga a pagar a favor de 
        “LA PARTE PROPIETARIA”, por concepto de renta u ocupación del inmueble, la cantidad de 
        <span class="resaltar2">
            ${!! evaluar($contrato->montoRenta, 'monto renta') !!}, (
            {!! evaluar($contrato->montoRenta, 'monto renta',false,'dinero') !!} 00/100 MONEDA NACIONAL) 
        </span>
        de manera mensual, misma que deberá ser cubierta 
        <span class="resaltar2">
            puntualmente el día 
            {!! evaluar($contrato->fechaIni, 'fecha inicial', true, 'dia') !!} de cada mes, 
        </span>
        durante todo el tiempo que permanezca vigente el presente convenio, 
        <span class="resaltar">
        sin necesidad de requerimiento previo. 
        </span>
        <span>{{ relleno(45) }}</span>
    </div>
    <div class="parrafo">
        En el supuesto de que las partes <span class="resaltar">acuerden prorrogar la vigencia</span> 
        del presente convenio, convienen expresamente que <span class="resaltar">la renta mensual 
        podrá ser objeto de modificación,</span> ya sea por <span class="resaltar">actualización, 
        ajuste o incremento</span>, atendiendo a las condiciones del mercado inmobiliario, al estado de 
        conservación del inmueble o a cualquier otro factor que las partes estimen pertinente, 
        <span class="resaltar">debiendo quedar dicho nuevo monto asentado por escrito</span> 
        en el convenio modificatorio o adenda correspondiente, la cual formará parte integrante 
        del presente instrumento y será obligatoria para ambas partes. 
        <span>{{ relleno(3) }}</span>
    </div>
    <div class="parrafo">
        La entrega del inmueble deberá realizarse en el <span class="resaltar">mismo estado físico, 
        de conservación y funcionamiento</span> en que actualmente se encuentra, incluyendo sus 
        instalaciones, accesorios y bienes muebles, respondiendo <span class="resaltar">“LA PARTE 
        OCUPANTE”</span> por cualquier <span class="resaltar">daño, deterioro o menoscabo</span> 
        que resulte de su <span class="resaltar">uso indebido, negligencia, descuido o mal 
        manejo,</span> quedando obligada a cubrir el costo de su reparación o reposición. 
        <span>{{ relleno(15) }}</span>
    </div>
    <div class="parrafo">
        En el supuesto de que <span class="resaltar">“LA PARTE OCUPANTE” desocupe el inmueble de 
        manera anticipada,</span> es decir, antes del vencimiento del plazo pactado, acepta 
        expresamente que la cantidad de 
        <span class="resaltar">
            ${!! evaluar($contrato->deposito, 'Depósito') !!}, (
            {!! evaluar($contrato->deposito, 'Depósito',false,'dinero') !!} 00/100 MONEDA NACIONAL) 
        </span>
        entregada a <span class="resaltar">“LA PARTE PROPIETARIA”</span> por concepto de 
        <span class="resaltar">depósito en garantía,</span> quedará <span class="resaltar">en favor 
        de esta última como pena convencional,</span> sin necesidad de declaración judicial, 
        <span class="resaltar">renunciando desde este momento a cualquier reclamación</span> al respecto. 
        <span>{{ relleno(3) }}</span>
    </div>
    <div class="parrafo">
        Asimismo, al término del plazo convenido, o en caso de desocupación anticipada, si 
        <span class="resaltar">“LA PARTE OCUPANTE” presenta cualquier adeudo pendiente,</span> 
        ya sea por <span class="resaltar">rentas, servicios, consumos, daños al inmueble o a 
        sus instalaciones,</span> o cualquier otro concepto imputable a su ocupación, 
        <span class="resaltar">deberá liquidarlo en su totalidad a más tardar un día natural 
        previo</span> a la entrega y restitución del inmueble, siendo condición indispensable 
        para tener por cumplida la obligación de entrega. 
        <span>{{ relleno(20) }}</span>
    </div>
    <div class="parrafo">
        La <span class="resaltar">falta de pago</span> de dichos adeudos faculta a 
        <span class="resaltar">“LA PARTE PROPIETARIA”</span> para 
        <span class="resaltar">retener el depósito,</span> sin perjuicio de 
        <span class="resaltar">exigir el pago de las cantidades que excedan dicho 
        monto,</span> conforme a derecho. 
        <span>{{ relleno(40) }}</span>
    </div>
    <div class="parrafo">
        <span class="resaltar">TERCERA.-</span> El predio mencionado únicamente podrá 
        ser utilizado para <span class="resaltar">DEPARTAMENTO,</span> por lo que 
        <span class="resaltar">“LA PARTE OCUPANTE”</span> no podrá destinarlo a otros 
        usos distintos, ni tampoco ceder o trasladar la posesión del mismo sin el 
        consentimiento dado por escrito por <span class="resaltar">“LA PARTE PROPIETARIA”</span> 
        en cada caso.
        <span>{{ relleno(40) }}</span>
    </div>
    <div class="parrafo">
        <span class="resaltar">CUARTA.-</span> El predio objeto de este convenio transaccional 
        cuenta con una serie de bienes muebles que son propiedad de 
        <span class="resaltar">“LA PARTE PROPIETARIA”,</span> los cuales de igual manera se 
        entregan en posesión por el mismo plazo del bien inmueble, cuyo inventario ya es del 
        conocimiento de <span class="resaltar">“LA PARTE OCUPANTE”,</span> que declara y otorga 
        que se obliga y compromete a entregar los bienes muebles mencionados en el párrafo 
        anterior a <span class="resaltar">“LA PARTE PROPIETARIA”</span> o a quien sus derechos 
        representen, en el mismo buen estado de conservación y funcionamiento en el que se 
        encuentran actualmente una vez que haya fenecido el plazo del presente Contrato de 
        Arrendamiento  o su prórroga en determinado supuesto.
        <span>{{ relleno(20) }}</span>
    </div>
    <div class="parrafo">
        En el supuesto que exista una pérdida total o algún daño a los bienes muebles e inmuebles. 
        <span class="resaltar">"LA PARTE OCUPANTE"</span> se obliga a pagar a los cinco días 
        naturales, contados a partir de que <span class="resaltar">"LA PARTE PROPIETARIA”</span> 
        tenga conocimiento del hecho, el valor que estime esta última, considerando los precios 
        del mercado en la fecha respectiva. De igual manera los comparecientes pactan que 
        <span class="resaltar">“LA PARTE OCUPANTE”</span> se hará cargo y responsable del 
        mantenimiento del aire acondicionado que se encuentra en el predio objeto del presente 
        convenio, mismo que se realizará cada seis meses contados a partir del inicio de su ocupación.
        <span>{{ relleno(15) }}</span>
    </div>
    <div class="parrafo">
        <span class="resaltar">QUINTA.-</span> Si <span class="resaltar">“LA PARTE PROPIETARIA”</span> 
        por cualquier motivo tuviera que ejecutar judicialmente esta transacción a fin de que 
        <span class="resaltar">“LA PARTE OCUPANTE” DESOCUPE Y ENTREGUE DESOCUPADO “EL INMUEBLE”, 
        “LA PARTE OCUPANTE”</span> pagará a <span class="resaltar">“LA PARTE PROPIETARIA”</span> 
        por cada día que transcurra sin entregarlo, la cantidad de
        <span class="resaltar">
            ${!! evaluar($contrato->penaEntrega, 'Penalización Entrega') !!}, (
            {!! evaluar($contrato->penaEntrega, 'Penalización Entrega',false,'dinero') !!} 00/100 MONEDA NACIONAL), 
        </span>
        desde el momento de la ejecución hasta que “EL INMUEBLE” sea entregado totalmente desocupado a 
        <span class="resaltar">“LA PARTE PROPIETARIA”.</span>
        <span>{{ relleno(3) }}</span>
    </div>
    <div class="parrafo">
        <span class="resaltar">SEXTA.-</span> Se estipula expresamente que 
        <span class="resaltar">“LA PARTE OCUPANTE”</span> no podrá cambiar la estructura del 
        predio en forma alguna, ni realizar mejoras ni instalaciones de ninguna especie, ni modificar 
        las ya existentes, sin el consentimiento específico y anticipado, en cada caso otorgado por 
        escrito por <span class="resaltar">“LA PARTE PROPIETARIA”.</span>
        <span>{{ relleno(40) }}</span>
    </div>
    <div class="parrafo">
        Todas las obras y mejoras que <span class="resaltar">“LA PARTE OCUPANTE”</span> llevare a cabo 
        en el predio quedarán a beneficio de <span class="resaltar">“LA PARTE PROPIETARIA”,</span> por 
        lo que no podrán ser retiradas por <span class="resaltar">“LA PARTE OCUPANTE”,</span> ni esta 
        tendrá derecho a cobrar ni <span class="resaltar">“LA PARTE PROPIETARIA”</span> la obligación 
        de pagar el costo de las mismas.
        <span>{{ relleno(40) }}</span>
    </div>
    <div class="parrafo">
        Asimismo, <span class="resaltar">“LA PARTE OCUPANTE”</span> tendrá el término de treinta días 
        contados a partir del inicio de la vigencia del convenio para reportar a la parte propietaria 
        cualquier desperfecto que tuviese el inmueble en sus instalaciones eléctricas, hidráulicas y 
        sanitarias (tales como llaves de agua, focos, contactos de luz, ventanas, cerraduras, 
        miriñaques, puertas entre otros) y quedando por lo tanto a cargo de 
        <span class="resaltar">“LA PARTE OCUPANTE”</span> el mantenimiento que requieran las mismas 
        con posterioridad a este plazo. En caso de que algún bien mueble sufra algún daño o desperfecto 
        grave o referente a instalaciones eléctricas o hidráulicas, el ocupante se obliga a comunicarlo 
        de forma inmediata a <span class="resaltar">“LA PARTE PROPIETARIA”</span> para que esté informada 
        sobre la forma en que se procederá a la reposición  o  reparación  de  estos.
        <span>{{ relleno(40) }}</span>
    </div>
    <div class="parrafo">
        <span class="resaltar">SÉPTIMA.- “LA PARTE OCUPANTE”,</span> reconoce haber recibido en perfectas 
        condiciones estructurales, de funcionamiento y a su entera conformidad para servir al uso 
        convenido respecto del bien inmueble objeto del presente convenio. Igualmente se pacta 
        expresamente que al predio objeto de este convenio no se le causará daños ni deterioros por 
        <span class="resaltar">“LA PARTE OCUPANTE”,</span> siendo responsable de los que se ocasionaren 
        por su culpa, dolo o negligencia, debiendo repararlos o reponer el daño causado a más tardar a 
        cinco días naturales posteriores.
        <span>{{ relleno(5) }}</span>
    </div>
    <div class="parrafo">
        <span class="resaltar">OCTAVA.-</span> Queda a cargo exclusivo de 
        <span class="resaltar">“LA PARTE OCUPANTE”,</span> el pago de contratos y consumo de cualquier 
        otro servicio que se utilice en el inmueble objeto de esta transacción, así como es a su cargo 
        el cuidado del mismo inmueble, sus partes y sus instalaciones para la conservación del buen estado 
        que actualmente guarda.
        <span>{{ relleno(20) }}</span>
    </div>
    <div class="parrafo">
        Las partes acuerdan que los servicios de energía eléctrica, internet, agua potable, calentador 
        de agua, mantenimiento del edificio y recolección de basura serán a cargo de 
        <span class="resaltar">“LA PARTE PROPIETARIA”.</span>
        <span>{{ relleno(45) }}</span>
    </div>
    <div class="parrafo">
        <span class="resaltar">NOVENA.-</span> El presente convenio podrá darse por vencido anticipadamente 
        si <span class="resaltar">“LA PARTE OCUPANTE”</span> incurriere en el incumplimiento de cualquiera 
        de las obligaciones contraídas en este contrato.
        <span>{{ relleno(50) }}</span>
    </div>
    <div class="parrafo">
        <span class="resaltar">DÉCIMA.-</span> Este convenio transaccional tiene respecto de las partes toda 
        la fuerza y validez de una sentencia ejecutoriada; es decir, de cosa juzgada, en los términos del 
        Código de Procedimientos Civiles del Estado de Yucatán.
        <span>{{ relleno(40) }}</span>
    </div>
    <div class="parrafo">
        <span class="resaltar">DÉCIMA PRIMERA.- La parte ocupante</span> entrega a 
        <span class="resaltar">“LA PARTE PROPIETARIA”,</span> en este acto, la cantidad de 
        <span class="resaltar">
            ${!! evaluar($contrato->deposito, 'Depósito') !!}, (
            {!! evaluar($contrato->deposito, 'Depósito',false,'dinero') !!} 00/100 MONEDA NACIONAL), 
        </span>
        misma que queda en poder de esta última en calidad de depósito en garantía, para asegurar el 
        cumplimiento de todas y cada una de las obligaciones asumidas por 
        <span class="resaltar">“LA PARTE OCUPANTE”</span> en el presente convenio.
        <span>{{ relleno(50) }}</span>
    </div>
    <div class="parrafo">
        Dicho depósito será devuelto a <span class="resaltar">“LA PARTE OCUPANTE” dentro de los cinco 
        días naturales posteriores</span> a la entrega material y jurídica del inmueble, siempre y cuando éste, 
        así como los bienes muebles e instalaciones, <span class="resaltar">sean restituidos en el mismo estado 
        de conservación y funcionamiento</span> en que actualmente se encuentran, y 
        <span class="resaltar">no exista adeudo alguno</span> por concepto de servicios, consumos, daños, 
        deterioros o cualquier otra obligación derivada del presente instrumento.
        <span>{{ relleno(30) }}</span>
    </div>
    <div class="parrafo">
        En caso de que, al momento de la entrega del inmueble, <span class="resaltar">“LA PARTE OCUPANTE” 
        presente adeudos pendientes o se detecten daños imputables a negligencia, uso indebido o falta de 
        mantenimiento, “LA PARTE PROPIETARIA” queda expresamente facultada para aplicar total o parcialmente 
        el depósito</span> al pago de dichos adeudos y a la reparación de los daños correspondientes, 
        <span class="resaltar">sin necesidad de autorización adicional ni declaración judicial,</span> lo anterior 
        <span class="resaltar">sin perjuicio del derecho de exigir el pago de las cantidades 
        que excedan el monto del depósito,</span> conforme a derecho.
        <span>{{ relleno(30) }}</span>
    </div>
    <div class="parrafo">
        <span class="resaltar">DÉCIMA SEGUNDA.-</span> Todos los pagos por honorarios, derechos, impuestos, 
        y demás gastos que se causen por este convenio y su ejecución, en su caso quedan a cargo de 
        <span class="resaltar">“LA PARTE OCUPANTE”.</span>
        <span>{{ relleno(45) }}</span>
    </div>
    <div class="parrafo">
        <span class="resaltar">DÉCIMA TERCERA.- “LA PARTE OCUPANTE”,</span> se obliga a cumplir con todas y 
        cada una de las disposiciones municipales y sanitarias, a fin de que el predio se encuentre en condiciones 
        higiénicas y habitables, siendo por su cuenta cualesquiera gastos, que fueren necesarios para cumplir con 
        estas disposiciones, cuya infracción dará motivo también a la ejecución de este convenio transaccional. 
        De igual manera <span class="resaltar">“LA PARTE OCUPANTE”</span> se obliga en este acto a no introducir 
        en el bien inmueble sustancias explosivas y a no utilizar el bien inmueble en forma que constituya un 
        perjuicio a los demás colindantes o que pudiese imputar mala reputación para el inmueble; el incumplimiento 
        por parte de <span class="resaltar">“LA PARTE OCUPANTE”</span> a cualesquiera de estas obligaciones será 
        suficiente para que <span class="resaltar">“LA PARTE PROPIETARIA”</span> rescinda el presente convenio.
        <span>{{ relleno(0) }}</span>
    </div>
    <div class="parrafo">
        <span class="resaltar">DÉCIMA CUARTA.-</span> Las comparecientes reconocen que cualquier testimonio 
        de la presente escritura debe ser considerado título ejecutivo, por lo que para su ejecución no requiere 
        de aprobación judicial, por tal motivo, la falta de cumplimiento de las obligaciones contraídas en la 
        propia transacción dará derecho a la ejecución respectiva, como si fuere sentencia ejecutoriada, 
        utilizando los medios de apremio que señala el capítulo de la Ejecución de las Sentencias del Código 
        de Procedimientos Civiles vigente en el Estado, incluyendo la fuerza pública en caso necesario.
        <span>{{ relleno(50) }}</span>
    </div>
    <div class="parrafo">
        <span class="resaltar">DÉCIMA QUINTA.-</span> En caso de procedimiento judicial derivado del presente 
        convenio, <span class="resaltar">“LA PARTE OCUPANTE”</span> acepta pagar mensualmente, en concepto de 
        gastos y costas del procedimiento, una cantidad igual a cincuenta salarios mínimos diarios vigentes a la 
        fecha del procedimiento.
        <span>{{ relleno(45) }}</span>
    </div>
    <div class="parrafo">
        <span class="resaltar">DÉCIMA SEXTA.- “LA PARTE OCUPANTE”</span> manifiesta y reconoce expresamente que 
        <span class="resaltar">única y exclusivamente en el supuesto de que incumpla con alguna de las 
        obligaciones económicas</span> asumidas en el presente convenio <span class="resaltar">y, además, 
        presente un adeudo exigible,</span> así en el supuesto que <span class="resaltar">se desconozca su 
        paradero por un periodo continuo mayor a dos días naturales,</span> sin haber dado aviso previo y 
        fehaciente de su paradero a <span class="resaltar">“LA PARTE PROPIETARIA”, autoriza de manera 
        expresa e irrevocable a esta última para acceder al interior del inmueble</span> objeto del presente 
        instrumento y <span class="resaltar">tomar posesión material del mismo,</span> con la única finalidad 
        de resguardar el bien y evitar mayores perjuicios.
        <span>{{ relleno(20) }}</span>
    </div>
    <div class="parrafo">
        La facultad conferida en la presente cláusula <span class="resaltar">no se considerará en ningún caso 
        como despojo,</span> toda vez que se ejerce con base en la autorización expresa de 
        <span class="resaltar">“LA PARTE OCUPANTE” y deriva del incumplimiento con adeudo previamente 
        existente,</span> sin perjuicio de las acciones legales que correspondan a 
        <span class="resaltar">“LA PARTE PROPIETARIA”</span> para la recuperación de adeudos, daños o la 
        restitución definitiva del inmueble conforme a derecho.
        <span>{{ relleno(3) }}</span>
    </div>
    <div class="parrafo">
        <span class="resaltar">DÉCIMA SÉPTIMA.- “LA PARTE OCUPANTE”</span> acepta y reconoce expresamente que, 
        en el supuesto de que el inmueble objeto del presente convenio <span class="resaltar">sea utilizado para 
        la comisión de actos ilícitos</span> y que dichos actos <span class="resaltar">sean legalmente 
        atribuibles a su responsabilidad,</span> y como consecuencia de ello <span class="resaltar">se inicie 
        y concluya en forma definitiva</span> un procedimiento de <span class="resaltar">extinción de dominio 
        a favor del Estado Mexicano,</span> en perjuicio directo de <span class="resaltar">“LA PARTE 
        PROPIETARIA”, se obliga desde este momento a indemnizar plenamente a esta última.</span> Dicha 
        indemnización consistirá en el <span class="resaltar">pago del valor total del inmueble,</span> 
        obligación que <span class="resaltar">nacerá a partir de la fecha en que quede firme la resolución 
        definitiva</span> que decrete la extinción de dominio, y se cumplirá mediante el pago del valor comercial 
        que tenga el bien inmueble en el <span class="resaltar">mercado inmobiliario al momento en que se realice 
        el pago,</span> valor que deberá ser determinado mediante <span class="resaltar">avalúo practicado por 
        perito valuador certificado,</span> designado de común acuerdo por las partes, o en su defecto, por 
        quien designe la autoridad competente.  
        <span>{{ relleno(45) }}</span>
    </div>
    <div class="parrafo">
        La presente obligación se establece <span class="resaltar">sin perjuicio de las demás responsabilidades 
        civiles, administrativas o penales</span> a que haya lugar en contra de 
        <span class="resaltar">“LA PARTE OCUPANTE”,</span> derivadas de los actos ilícitos que se le imputen, 
        y tendrá el carácter de <span class="resaltar">indemnización por daños y perjuicios, conforme a derecho.</span>
        <span>{{ relleno(10) }}</span>
    </div>
    <div class="parrafo">
        <span class="resaltar">DÉCIMA OCTAVA.-</span> Para cualquier asunto o controversia con relación a este 
        convenio, ambas partes contratantes se someten expresamente a la jurisdicción y competencia de los Jueces 
        y Tribunales de esta ciudad de Mérida, Yucatán, con renuncia de cualquier fuero que la ley les conceda en 
        razón de domicilio o vecindad.
        <span>{{ relleno(30) }}</span>
    </div>
    <div class="parrafo">
        <span class="resaltar">DÉCIMA NOVENA.-</span> Acuerdan ambas partes: a) Que a petición de cualquiera de 
        ellas, podrán ocurrir ante un Notario Público, a efecto de protocolizar el presente contrato, siendo a 
        cargo de ambas los gastos y honorarios que se originen por la misma; b) Que los gastos, impuestos y 
        honorarios de apoderados, abogados, procuradores y notarios que tenga que erogar alguna de las partes 
        en virtud de que la otra no cumpla con alguna de las obligaciones y tenga que rescindir el contenido que 
        este documento se refiere, será por cuenta única y exclusiva de la parte infractora.
        <span>{{ relleno(1) }}</span>
    </div>
    <div class="parrafo">
        <span class="resaltar">VIGÉSIMA.-</span> Las partes, aceptan las obligaciones contraídas en este contrato 
        en los términos de las anteriores cláusulas.
        <span>{{ relleno(40) }}</span>
    </div>
    <div class="parrafo">
        <span class="resaltar">VIGÉSIMA PRIMERA.-</span> Las partes declaran y otorgan que han celebrado el 
        convenio consignado en esta escritura, basado en la libertad de contratación que garantiza la Constitución 
        Política de los Estados Unidos Mexicanos, la cual rige sobre cualquiera de las disposiciones legales aplicables.
        <span>{{ relleno(50) }}</span>
    </div>
    <div class="parrafo">
        <span class="resaltar">VIGÉSIMA SEGUNDA.-</span> Todos los términos con mayúscula tendrán el significado 
        que se les atribuye en este contrato. Los encabezados de las cláusulas se incluyen únicamente como 
        referencia al contenido de dichas cláusulas y de ninguna manera afectarán el significado, contenido 
        o interpretación de los acuerdos contenidos en las mismas.
        <span>{{ relleno(10) }}</span>
    </div>
        <div class="parrafo">
        <span>{{ relleno(25) }}</span> G E N E R A L E S
        <span>{{ relleno(25) }}</span>
    </div>
    <div class="parrafo">
        <span class="resaltar">I.- {!! evaluar($contrato->propietario?->propietario, 'propietario') !!}</span>, 
        quien manifestó {!! evaluar($contrato->propietario?->generales, 'generales propietario') !!} 
        <span>{{ relleno(4) }}</span>
    </div>
    <div class="parrafo">
        <span class="resaltar2">II.- {!! evaluar($contrato->inquilino?->inquilino, 'inquilino') !!}, 
        quien manifestó {!! evaluar($contrato->inquilino?->generales, 'generales inquilino') !!}</span> 
        <span>{{ relleno(20) }}</span>
    </div>
    <div class="parrafo resaltar">
        Y PARA CONSTANCIA Y CONFORMIDAD, SUSCRIBIMOS EN DUPLICADO E IMPRIMIMOS LA HUELLA DE NUESTRO DEDO 
        PULGAR DERECHO, EL PRESENTE DOCUMENTO, EN LA CIUDAD DE MÉRIDA, YUCATÁN, ESTADOS UNIDOS MEXICANOS, A LOS 
        {!! evaluar($contrato->fechaIni, 'fecha inicial', false, 'fecha') !!}.
        <span>{{ relleno(40) }}</span>
    </div>
    <div class="parrafoCenter resaltar">
        “LA PARTE PROPIETARIA”<br><br>
        <span class="parrafoCenter resaltar">
            ____________________________________________
        </span><br>
        {!! evaluar($contrato->propietario?->propietario, 'propietario') !!}
    </div>
    <div class="parrafoCenter resaltar">
        “LA PARTE OCUPANTE”<br><br>
        <span class="parrafoCenter resaltar">
            ____________________________________________
        </span><br>
        <span class="parrafoCenter resaltar">
            {!! evaluar($contrato->inquilino?->inquilino, 'inquilino') !!}
        </span>
    </div>
</body>
</html>
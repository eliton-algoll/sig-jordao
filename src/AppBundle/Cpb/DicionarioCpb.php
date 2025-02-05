<?php

namespace AppBundle\Cpb;

/** 
 * Dicionário do Arquivo CPB para utilização no sistema sem a necessidade de olhar na documentação
 */
final class DicionarioCpb
{
    /**
     *
     * Vide documento de regra de negócio [RGN72] (http://svn.saude.gov.br/svn/SIGPET/Branches/Documentacao/Requisitos/SIGPET_DRN.docx)
     * 
     * @var array 
     */
    static public $dicionario = [
        'CS-DEPE-SAQUE' => [            
            'descricao' => 'Código da dependência onde foi realizado o primeiro saque do benefício.',                        
        ],
        'CS-EPECIE' => [            
            'descricao' => 'Código da dependência onde foi realizado o primeiro saque do benefício.',            
        ],
        'CS-MEIO-PAGTO' => [            
            'descricao' => 'Classificador do meio de pagamento.',
            'values'    => [
                'Cartão Magnético' => '01',
            ],
            'default'   => '01',
        ],
        'CS-NATUR-CREDITO' => [
            'descricao' => 'Sequencial crescente do crédito dentro do mês com mesmo NIB / DT-FIM-PERIODO / DT-INI-PERIODO.',
        ],
        'CS-OCORRENCIA' => [
            'descricao' => 'Classificação da forma de pagamento ou motivo de ão pagamento',
            'values'    => [
                'Pagamento efetuado' => '1',
                'Orgão pagador inválido' => '2',
                'Não pagamento devido à inconsistência no lote de créditos' => '3',
                'Não pagamento devido ao vencimento da validade do crédito' => '4',
                'Não pagamento devido ao bloqueio do crédito sem o respectivo desbloqueio dentro do prazo de validade' => '5',
                'Não utilizado' => '6',
                'Não utilizado' => '7',
                'Não utilizado' => '8',
                'Não pagamento por falta de dados cadastrais' => '9',
            ],            
        ],
        'CS-ORIGEM-ORCAMENTO' => [
            'descricao' => 'Classificador da origem orçamentária.',
            'default'   => '01',
        ],
        'CS-RESP-BLOQUEIO' => [
            'descricao' => 'Classificador do motivo do não bloqueio.',
            'values'    => [
                'Crédito não localizado' => '01',
                'Crédito com pagamento efetuado antes do recebimento do lote de bloqueio/desbloqueio03 - Crédito com data de validade vencida' => '02',
                'Bloqueio/desbloqueio efetuado com sucesso' => '09',
            ],            
        ],
        'CS-TIPO-CREDITO' => [
            'default' => '03',
        ],
        'CS-TIPO-DADO-CAD' => [
            'default' => '03',
        ],
        'CS-TIPO-LOTE' => [
            'descricao' => 'Classificador do tipo de informação contida no lote.',
            'values'    => [
                'Lote de retorno de créditos pagos' => '01',
                'Lote de retorno de créditos vencidos' => '02',
                'Lote de retorno de créditos inconsistentes' => '03',
                'Lote de retorno de créditos “em ser”' => '04',
                'Lote de remessa de créditos' => '20',
                'Lote de remessa de dados cadastrais' => '21',
                'Lote de remessa de bloqueio' => '40',
                'Lote de remessa de desbloqueio' => '41',
                'Lote de retorno de resposta de bloqueio' => '42',
                'Lote de retorno de resposta de desbloqueio' => '43',
                'Lote de retorno de comando de crédito' => '80',
                'Lote de retorno de comando de cadastro' => '81',
                'Lote de retorno de comando de bloqueio' => '82',
                'Lote de retorno de comando de desbloqueio' => '83',
                'Lote de retorno de saques efetuados' => '90',
            ],
        ],
        'CS-TIPO-REGISTRO' => [
            'descricao' => 'Classificador do tipo de registro',
            'values'    => [
                'Registro do tipo HEADER' => '1',
                'Registro do tipo DETALHE' => '2',
                'Registro do tipo TRAILER' => '3',
            ],
        ],
        'CS-TIPO-SERVICO' => [
            'descricao' => 'Tipo de serviço prestado para efetuar o pagamento',
            'values'    => [
                'Pagamento por cartão magnético universal' => '3',
            ],
            'default'   => '3',
        ],
        'CS-UNID-MONET' => [
            'descricao' => 'Classificador da unidade monetária do VL-LIQ-PAGTO e do VL-LIQ-CRÉDITO',
            'values'    => [
                'Real (R$)' => '08',
            ],
            'default'   => '08',
        ],
        'DT-COMP-MOVTO' => [
            'descricao' => 'Ano/mês a que se refere a ocorrência (pagamento, vencimento ou recusa) no formato AAAAMM.',
        ],
        'DT-FIM-PERIODO' => [
            'descricao' => 'Data da finalização do período a que se refere o pagamento. Formato: AAAAMMDD.',
        ],
        'DT-FIM-VALIDADE' => [
            'descricao' => 'Data de finalização do prazo de validade do crédito, ou seja, data em que o pagamento deixa de estar disponível ao beneficiário. Formato: AAAAMMDD',
        ],
        'DT-GRAVACAO-LOTE' => [
            'descricao' => 'Data de gravação do lote (data em que o arquivo foi gravado). Formato: AAAAMMDD.',
        ],
        'DT-INI-PERIODO' => [
            'descricao' => 'Data início do período a que se refere o pagamento. Formato AAAAMMDD.',
        ],
        'DT-INI-VALIDADE' => [
            'descricao' => 'Data do início da validade do crédito, ou seja, data a partir da qual o crédito deve estar disponível para pagamento. Formato: AAAAMMDD.',
        ],
        'DT-MOV-CREDITO' => [
            'descricao' => 'Data em que o crédito foi calculado. Formato: AAAAMMDD.',
        ],
        'DT-NASC' => [
            'descricao' => 'Data de nascimento do beneficiário no formato AAAAMMDD',
        ],
        'DT-OCORRENCIA' => [
            'descricao' => 'Data do pagamento, recusa ou vencimento do crédito no formato AAAAMMDD',
        ],
        'DT-SAQUE' => [
            'descricao' => 'Data em que foi efetuado o primeiro saque do benefício. FORMATO: AAAAMMDD',
        ],
        'DT-ULT-ATU-END' => [
            'descricao' => 'Data da última atualização do endereço do beneficiário no formato AAAAMMDD (opcional)',
        ],
        'ID-AGENCIA-CONV' => [
            'descricao' => 'Identificador da agência do convênio associada ao benefício.',
        ],
        'ID-BANCO' => [
            'descricao' => 'Número do Banco',            
            'default'   => '001',
        ],
        'ID-BLOQUEIO' => [
            'descricao' => 'Código identificador do bloqueio (para uso do convenente - este código será devolvido no lote de resposta de bloqueio).',
        ],
        'ID-NIT' => [
            'descricao' => 'Número identificador alternativo para o beneficiário. O uso deste campo depende de acordo entre o banco e o convênio.',
        ],
        'ID-ORGAO- PAGADOR' => [
            'descricao' => 'Código identificador do órgão pagador (ag. Bancária) associado ao beneficiário como domicílio bancário. Não informar DV.',
        ],
        'IN-CR-BLOQUEADO' => [
            'descricao' => 'ndicador de bloqueio. Quando indicado o bloqueio, o saque não será permitido, até a liberação, por parte do convenente.',
            'values'    => [
                'Crédito NÃO bloqueado' => '1',
                'Crédito bloqueado' => '2',
            ],
        ],
        'IN-PIONEIRA' => [
            'descricao' => 'Indicativo de situação da agência domicílio do beneficiário',
            'values'    => [
                'Não pioneira' => '0',
                'Pioneira' => '1',
            ],
        ],
        'IN-PRESTACAO-UNICA' => [
            'descricao' => 'Indicador da continuidade ou não do benefício.',
            'values'    => [
                'Prestação Continuada sem Previsão de Cessação' => '1',
                'Prestação Única. Quando este indicador for informado, não será gerado cartão para o beneficiário.' => '3',
            ],
        ],
        'NM-BENEFICIARIO' => [
            'descricao' => 'Nome do Beneficiário que faz jus ao crédito do benefício.',
        ],
        'NM-MAE' => [
            'descricao' => 'Nome da mãe do Beneficiário',
        ],
        'NU-CEP' => [
            'descricao' => 'CEP de residência do beneficiário',
        ],
        'NU-COD-CONV' => [
            'descricao' => 'Código do convênio atribuído pelo Banco.',
        ],
        'NU-CONTA' => [
            'descricao' => 'Número da conta corrente para crédito do benefício',
        ],
        'NU-CPF' => [
            'descricao' => 'Número do CPF do beneficiário',
        ],
        'NU-CTRL-CRED' => [
            'descricao' => 'Número de controle do crédito informado pelo convenente.',
        ],
        'NU-CTRL-TRANS' => [
            'descricao' => 'Número para o controle operacional da transmissão de arquivos. Deverá ser preenchido com número sequencial crescente e consecutivo, iniciado em “000001”, atribuído de forma independente a cada tipo de lote transmitido. Funcionará como um contador, que será sempre incrementado na próxima transmissão do mesmo tipo de lote, independente da data de ocorrência desta transmissão.',
        ],
        'NU-DIA-UTIL' => [
            'descricao' => 'Dia útil dentro do mês a partir do qual estará disponível o benefício. Informação para constar no cartão. Obs.: O sistema não utilizará esta informação para alterar o prazo de validade do benefício. O prazo será definido no lote de créditos por meio das datas de início e fim de validade dos créditos.'
        ],
        'NU-NIB' => [
            'descricao' => 'Numérico identificador do benefício (informar o número mais o DV). O cálculo do DV é feito a partir do módulo 11. Se o DV calculado no módulo 11 for X, substituir por 0.',
        ],
        'NU-SEQ-CREDITO' => [
            'descricao' => 'Número sequencial de crédito informado pelo convenente.',            
        ],
        'NU-SEQ-LOTE' => [
            'descricao' => 'Número sequencial crescente atribuído aos lotes gravados em uma mesma data. O primeiro lote do arquivo receberá sequencial “01”.',
        ],
        'NU-SEQ-REGISTRO' => [
            'descricao' => 'Deverá ser preenchido com sequencial crescente do registro dentro do lote. O primeiro registro do lote receberá sequencial “0000001”. Quando mudar o lote, este sequencial volta a zero.',
        ],
        'NU-SEQ-RMS' => [
            'descricao' => 'Número sequencial da remessa informado pelo convenente.',
        ],
        'QT-REG-DETALHE' => [
            'descricao' => 'Quantidade de registros do tipo detalhe gravados no lote',
        ],
        'SIT-CMD' => [
            'descricao' => 'Situação de cada comando enviado pelo convenente.',
            'values'    => [
                'Registro OK' => '0000',
                'Arquivo sem trailler' => '0001',
                'SEQ-RGTO não numérico' => '0002',
                'SEQ-RGTO header inválido' => '0003',
                'TPO-LOTE não numérico' => '0004',
                'TPO-LOTE inválido' => '0005',
                'ID-BANCO não numérico' => '0006',
                'ID-BANCO inválido' => '0007',
                'MEIO-PAGTO não numérico' => '0008',
                'MEIO-PAGTO inválido' => '0009',
                'DT-GRAV-LOTE não numérico' => '0010',
                'DT-GRAV-LOTE inválida' => '0011',
                'NR-SEQ-LOTE não numérico' => '0012',
                'NR-RMS-BLOQ não numérico' => '0013',
                'Crédito sem cadastro' => '0014',
                'NU-CTRL-TRANS não numérico' => '0015',
                'NU-CTRL-TRANS inválido' => '0016',
                'Erro no header' => '0017',
                'Arquivo sem header' => '0018',
                'SEQ-RGTO no detalhe inválido' => '0019',
                'TPO-LOTE diferente do header' => '0020',
                'TP-CRED não numérico' => '0021',
                'TP-CRED inválido' => '0022',
                'NUM-BENEF não numérico' => '0023',
                'NUM-BENEF diferente de 0' => '0024',
                'NUM-BENEF diferente de zeros' => '0025',
                'DTA-FIM-PER não numérico' => '0026',
                'DTA-FIM-PER inválida' => '0027',
                'DTA-INI-PER não numérico' => '0028',
                'DTA-INI-PER inválido' => '0029',
                'NATUREZA não numérico' => '0030',
                'DTA-CRED não numérico' => '0031',
                'DTA-CRED inválido' => '0032',
                'ORG-PAGD não numérico' => '0033',
                'VLR-BENEF não numérico' => '0034',
                'VLR-BENEF igual a 0' => '0035',
                'UNID-MONET não numérico' => '0036',
                'UNID-MONET inválido' => '0037',
                'DTA-FIM-VLDD não numérico' => '0038',
                'DTA-FIM-VLDD inválida' => '0039',
                'DTA-INI-VLDD não numérico' => '0040',
                'DTA-INI-VLDD inválida' => '0041',
                'IND-BLOQ não numérico' => '0042',
                'IND-BLOQ inválido' => '0043',
                'CONTA não numérica' => '0044',
                'CONTA inválida' => '0045',
                'DV-CONTA inválida' => '0046',
                'ORIG-ORCAM não numérico' => '0047',
                'IND-PIONEIRA não numérico' => '0048',
                'IND-PIONEIRA inválido' => '0049',
                'ID-NIT não numérico' => '0050',
                'ID-NIT diferente de 0' => '0051',
                'ID-NIT igual a 0' => '0052',
                'CS-ESPECIE não numérico' => '0053',
                'ORG-PAGD-CAD não numérico' => '0054',
                'AGEN-INSS não numérico' => '0055',
                'IND-PREST- UNICA não numérico' => '0056',
                'IND-PREST- UNICA inválido' => '0057',
                'CONTA-CAD não numérico' => '0058',
                'CONTA-CAD inválido' => '0059',
                'DV-CONTA-CAD inválido' => '0060',
                'DV-CPF inválido' => '0061',
                'NM-RECEBEDOR em branco' => '0062',
                'ENDERECO em branco' => '0063',
                'CEP não numérico' => '0064',
                'DATA-NASC não numérico' => '0065',
                'DATA-NASC inválida' => '0066',
                'NM-MAE em branco' => '0067',
                'DT-ULT-ATU-END não numérico' => '0068',
                'DT-ULT-ATU-END inválido' => '0069',
                'NUM-DIA não numérico' => '0070',
                'DTA-FIM-BLOQ não numérico' => '0071',
                'DTA-FIM-BLOQ inválido' => '0072',
                'DTA-INI-BLOQ não numérico' => '0073',
                'DTA-INI-BLOQ inválido' => '0074',
                'ID-BLOQ não numérico' => '0075',
                'ID-BLOQ inválido' => '0076',
                'Arquivo sem header' => '0077',
                'QTD-REG-DET não numérico' => '0078',
                'QTD-REG-DET não confere' => '0079',
                'VLR-REG-DET não numérico' => '0080',
                'VLR-REG-DET TRL não confere' => '0081',
                'NUM-SEQ-LOTE(TRL) não numérico' => '0082',
                'NUM-SEQ-LOTE(TRL) não confere com header' => '0083',
                'NUM-NB e NUM-NIT iguais a 0' => '0084',
                'TPO-RGTO inválido' => '0085',
                'TP-CAD não numérico' => '0086',
                'TP-CAD inválido' => '0087',
                'Código sinônimo difere do cadastro' => '0088',
                'Código sinônimo não encontrado' => '0089',
                'Código agência pioneira não confere' => '0090',
                'Crédito duplicado' => '0091',
                'Conta não confere com cadastro' => '0092',
                'SEQ-RGTO no trailler inválido' => '0093',
                'Código CPB não numérico' => '0094',
                'COD-CONV não cadastrado' => '0095',
                'DV BENEFICIO não confere' => '0096',
                'DV NIT não confere' => '0097',
                'NUM-DIA inválido' => '0098',
                'Não é uma AG-CC do MESTRE' => '0099',
                'DV-CONTA inválida' => '0100',
                'DV-CONTA-CAD inválida' => '0101',
                'CRD-NR-CC/AG inválida' => '0102',
                'Conta redirecionada para outra UF' => '0103',
                'MEIO-PAGTO inválido para este convênio' => '0104',
                'Crédito c/ CD-TIP-DSPD diferente do cadastro' => '0105',
                'Competência inválida' => '0106',
                'IND-REPRES-LEGAL inválido' => '0107',
                'Natureza inválida' => '0108',
                'Validade do crédito inválida' => '0109',
                'Tipo de registro inválido' => '0110',
                'Erro no trailler' => '0111',
                'CPF não numérico' => '0112',
                'Número do benefício não informado' => '0113',
                'Crédito vencido' => '0114',
                'Origem orçamento inválida' => '0115',
                'Agência não cadastrada' => '0116',
                'Cadastro com erro' => '0117',
                'Origem do bloqueio não numérico' => '0118',
                'Validade do crédito maior que 90 dias' => '0119',
            ]
        ],
        'SIT-RMS' => [
            'descricao' => 'Situação da remessa',
            'values'    => [
                'Aceita' => '1',
                'Cancelada' => '2',
            ],
        ],
        'TE-BAIRRO' => [
            'descricao' => 'Bairro do beneficiário',            
        ],
        'TE-ENDERECO' => [
            'descricao' => 'Endereço do Beneficiário'
        ],
        'VL-CREDITO' => [
            'descricao' => 'Valor do crédito disponibilizado. No caso de o convênio arcar com a CPMF devida, o valor do imposto deverá ser adicionado a este campo. Formato: 9(10) V99.',
        ],
        'VL-REG-DETALHE' => [
            'descricao' => 'Somatório de todos os valores VL-LIQ-PAGTO ou VL-LIQ-CRÉDITO dos registros tipo detalhe. Formato: 9(15) V99.',
        ],
    ];
    
    /**
     * 
     * @param string $campo
     * @return boolean
     */
    static public function has($campo)
    {
        return array_key_exists($campo, static::$dicionario);
    }
    
    /**
     * 
     * @param string $campo
     * @return string
     */
    static public function get($campo)
    {
        return static::has($campo) ? static::$dicionario[$campo] : null;
    }
    
    /**
     * 
     * @param string $campo
     * @return string     
     */
    static public function getDescricao($campo)
    {
        return static::has($campo) && (isset(static::$dicionario[$campo]['descricao'])) ? static::$dicionario[$campo]['descricao'] : null;
    }
    
    /**
     * 
     * @param string $campo
     * @return array
     */
    static public function getValues($campo)
    {
        return static::has($campo) && (isset(static::$dicionario[$campo]['values'])) ? static::$dicionario[$campo]['values'] : [];
    }
    
    /**
     * 
     * @param string $campo
     * @return string
     */
    static public function getDefault($campo)
    {
        return static::has($campo) && (isset(static::$dicionario[$campo]['default'])) ? static::$dicionario[$campo]['default'] : null;
    }
    
    /**
     * 
     * @param string $campo
     * @param mixed $value
     * @return string
     */
    static public function getValueDescricao($campo, $value)
    {
        $value = (array) $value;
        
        return array_filter(static::getValues($campo), function ($v) use ($value) {                        
            return in_array($v, $value);
        });
    }
}

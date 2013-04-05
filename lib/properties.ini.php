;<?php
; die();
; /*

[globals]
ins_url = "http://pagamenti.unicredito.it/initInsert.do"
ins_MOTO_url = "https://pagamenti.unicredito.it/richiesta_remota_autorizzazione.do"
remote_cmd_url = "https://pagamenti.unicredito.it/backoffice/servizi/execute_remote_command.do"

[insert]
mandatory[] = "numeroCommerciante"
mandatory[] = "userID"
mandatory[] = "password"
mandatory[] = "numeroOrdine"
mandatory[] = "totaleOrdine"
mandatory[] = "valuta"
mandatory[] = "flagDeposito"
mandatory[] = "urlOk"
mandatory[] = "urlKo"
mandatory[] = "tipoRispostaApv"
mandatory[] = "flagRiciclaOrdine"
mandatory[] = "stabilimento"

optional[] = "tipoPagamento"
optional[] = "causalePagamento"
optional[] = "langCompratore"
optional[] = "emailCompratore"

[response]
ok[] = "numeroOrdine"
ok[] = "numeroCommerciante"
ok[] = "stabilimento"
ok[] = "esito"
ok[] = "dataApprovazione"

ko[] = "numeroOrdine"
ko[] = "numeroCommerciante"
ko[] = "stabilimento"
ko[] = "esito"

; */
;?>

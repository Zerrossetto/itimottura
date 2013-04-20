;<?php
; die();

; ATTENZIONE
; FILE DI CONFIGURAZIONE DEL SISTEMA DI PAGAMENTI
;
; Qui sotto troverai le istruzioni per modificare, aggiungere o
; eliminare le descrizioni utilizzate dal sistema di pagamenti
; attraverso le varie funzionalità di pagamento.

[lista_classi]

; Questa lista rappresenta le classi disponibili al momento.
; La sintassi da seguire per far comparire una classe nei menù
; a tendina è quella a seguire:
;
; classi[] = <lettera della sezione>

classi[] = A
classi[] = B
classi[] = C
classi[] = D
classi[] = E
classi[] = F
classi[] = G



[tariffe_comodato]

; Questa sezione rappresenta le tariffe di comodato divise per anno.
; E' possibile specificare tariffe diverse classe per classe o in base
; all'anno, se per una classe non viene specificata nessuna tariffa speciale
; il sistema prende il valore dell'anno corrispondente.
;
; ATTENZIONE: Le righe 1, 2, 3, 4, 5 sono obbligatorie, mentre le successive
; possono essere aggiunte a piacimento. La sintassi da seguire è questa:
;
; <anno><sezione> = <tariffa di comodato>
;
; Dove il valore <tariffa di comodato> può essere espresso con o senza la virgola

1 = 50
2 = 50
3 = 50
4 = 50
5 = 50



[lista_viaggi]

; Una lista descrittiva dei viaggi selezionabili da una classe.
; Mantenere per cortesia la sintassi utilizzata per la descrizione
;  dei viaggi, ossia:
;
; <importo> = "<classe><descrittore del viaggio a piacere>"
;
; Dove il valore <importo> può essere espresso con o senza la virgola

200.00 = "1A - Val camonica 12/05/2013"
300.50 = "2B - Bergamo 15/02/2013"
400.00 = "5D - Praga 06/04/2013"



; ATTENZIONE
; Seguono le sezioni di dizionario per la pagina di landing, non modificare.

[COM_]
isPupil = true
titleOK = "Richiesta di comodato %s autorizzata"
titleKO = "Richiesta di comodato %s NON autorizzata"
pagename = "comodato.php"
pageheader = "Richiesta Comodato ID %s"
pagebody = "Hai completato la procedura di richiesta di comodato d&apos;uso utilizzando i seguenti dati:"

[VIA_]
isPupil = true
titleOK = "Richiesta viaggio di istruzione %s autorizzata"
titleKO = "Richiesta viaggio di istruzione %s NON autorizzata"
pagename = "viaggi.php"
pageheader = "Viaggio di istruzione ID %s"
pagebody = "Hai completato la procedura di richiesta per il viaggio di istruzione utilizzando i seguenti dati:"

[SPO_]
isPupil = false
titleOK = "Pagamento quota sponsor %s completata"
titleKO = "Pagamento quota sponsor %s NON completata"
pagename = "sponsor.php"
pageheader = "Quota sponsor ID %s"
pagebody = "Hai completato la procedura di pagamento della quota di sponsorizzazione utilizzando i seguenti dati:"

[LOC_]
isPupil = false
titleOK = "Quota uso locali %s autorizzata"
titleKO = "Quota uso locali %s NON autorizzata"
pagename = "uso-locali.php"
pageheader = "Quota uso locali ID %s"
pagebody = "Hai completato la procedura di pagamento della quota prevista per l&apos;utilizzo dei locali con i seguenti dati:"

;?>
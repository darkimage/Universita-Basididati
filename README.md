## Progetto Basi di Dati e Web
Il progetto verte sulla creazione di un sito web che utilizza un **database relazionale** per lo storage dei dati e una logica in **php** per la presentazione all'utente.

Sono stati creati **tre principali sistemi cooperanti** per gestire queste necessita':

 - Utilizzo del **Pattern MVC** (Model-View-Controller)
 - Creazione di un **Custom Templating Engine** (Templating Classes)
 - Creazione di **Domain Classes** per l'astrazione del DB

La relazione completa del progetto e disponibile [qui](https://github.com/darkimage/Universita-Basididati/raw/master/documents/basi_di_dati.pdf).

#### Testo traccia progetto

> Il progetto descrive un sistema di gestione dei compiti (Tasks) di
> un’azienda nel quale ad un utente o a un Gruppo di utenti viene
> assegnato un o più compiti da svolgere. Per ogni compito vengono
> salvati la data di inizio, fine e scadenza cosi come il nome e la
> descrizione. Un compito può contenere oltre a queste informazioni
> anche una lista di altri compiti (che non contenga il compito alla
> quale viene associata) che deve essere completata prima di completare
> il compito che la contiene. Ogni Compito fa parte di un solo progetto
> e un progetto può avere più compiti che devono essere completati prima
> di dichiarare il progetto concluso. Per ogni progetto vengono anche
> salvati dati come nome, descrizione e le relative date di creazione,
> completamento e scadenza. Un utente quale e stata assegnata un compito
> può decidere di condividere ad un altro utente quel compito che gli è
> stato assegnato per potersi far aiutare e per ogni Utente vengono
> salvati i dati relativi a nome, cognome, nome utente, password e data
> di nascita.

## Fasi di progettazione e produzione
Durante questa fase si sono analizzate tutte le specifiche richieste dalla traccia del progetto (come se fossero effettivamente richieste del commitente), si e' adoperata una distinzione tra **Requisiti del Database** per lo storage di dati (DBMS) e **Requisiti dell' Applicazione** (parte in PHP e presentazione ad utente finale).

Sono state utilizzate le seguenti librerie per uno sviluppo più coerente e anche per il rispetto degli standard WEB: 

 - **Bootstrap**: libreria (Sass) sviluppata da twitter per costruire interfacce front-end mobilefriedly e responsive. (https://getbootstrap.com/) 
 - **Fontaweasome**: liberia di icone vettoriali (SVG) accessibili da Sass/css e da html tramite l’attributo class. (https://fontawesome.com/) 
 - **JQuery**: libreria multipurpose javascript, utilizzata ampiamente nel progetto per realizzare la comunicazione tra back-end e front-end (https://jquery.com/) 
 -  **I18n**: libreria PHP per la realizzazione della internazionalizzazione del progetto permette di specificare file chiave-valore per ogni lingua e di cambiare lingua utilizzando i cookies o i parametri GET. (https://github.com/Philipp15b/php-i18n)
 - **Scssphp**: compilatore di Sass in CSS usato per compilare bootstrap e tutti i file Sass scritti per l’applicazione, usato in concomitanza dei file .htacess per accedere a file CSS compilati on demand (https://github.com/leafo/scssphp)

 Il progetto fa uso quindi di uno stack WEB di tipo WAMP (Windows | Apache | MySQL | PHP) .

Per maggiori dettagli fare riferimento alla relazione finale disponibile [qui](https://github.com/darkimage/Universita-Basididati/raw/master/documents/basi_di_dati.pdf).

## Custom Templating Engine
Questo sistema e' stato creato consentire una modulare composizione delle pagine da mostrare all’utente, il concetto su cui si basa e il concetto di Componente (**DOM Tag**) ogni tag del template viene processato dalla classe **tagProcessor** e nel caso sia un tag custom (ovvero che rientra nel pattern *t-customtag*) viene eseguita la funzione render della classe corrispondente (che deve essere una classe che estende la classe **htmlTag**). Per ogni DOM tag (anche quelli standard html) vengono processati gli attributi che possono essere considerati gli input dei Componenti questi dati sono poi passati ad ogni tag che è figlio di quello appena processato consentendo cosi di ottenere uno scope per i dati e di poter creare un nesting logico dei tags. 

Grafico del processing dei tag DOM (tagProcessor class):
![templating_scheme](https://github.com/darkimage/Universita-Basididati/raw/master/documents/images/templating_sceheme.png)

#### Classi del templating system del progetto:
 - **PageModel (class)**: questa che viene instaziata e popolata con i dati da passare alla view che poi verrà processata dalla classe tagProcessor. È possibile fare il nesting di più pageModels semplicemente facendo il render di un altro pageModel nel body del padre. Questa classe si preoccupa anche di caricare gli asset (script o file di stile CSS o immagini …) definiti nella proprietà resources mentre i dati sono messi in un array associativo chiave valore nella proprietà model questi dati poi possono essere referenziati negli attributi dei Componenti DOM (custom tags e anche stardard html tags) con la chiave associata, possono essere passati dati anche complessi non solo stringhe o numeri ma anche array, classi, enumeratori o qualsiasi altro costrutto del PHP. o 
	 - **Esempi di referenziazione dei dati:** 
		 -  **@{variabile:[default]}** questa scrittura è possibile utilizzarla solo negli attributi dei tags DOM, significa che verrà passato il valore del template referenziato dalla nome della chiave variabile nell’array. 
		 -  **${codice php valido}** questa scrittura è utilizzabile ovunque sia negli attributi dei tag sia in qualsiasi altra parte del codice html in base alla sua posizione durante il parsing verra’ prodotto il corrispettivo DOM valido. 
		 -  **@{variabile:[${return ‘ciao’}]}** è possibile fare il nesting delle due scritture.
 
 - **tagProcessor (class)**: questa classe si occupa di processare tutti i tag DOM presenti nel tamplate dichiarato dalla classe PageModel. Utilizza la ricorsione per il processing e si occupa anche di copiare il model dei tag padri nei tag figli e di ricomporre un documento DOM valido.  htmlTag (abstract): questa è la classe base che ogni tag deve estendere per essere utilizzato e riconosciuto durante il processing della classe tagProcessor. Ogni classe che estende htmlTag deve implementare il metodo getModel().

## Domain Classes
la parte del MVC che riguarda i dati e trattata dal file dbConnection.php che comprende le classi: 

 - **dbConnection (singleton)**: questa classe si occupa di gestire la connessione con il database e provvedere a una interfaccia per l’esecuzione delle query. questa classe e un sigleton perche viene chiamata molte volte ma non è necessario che sia instanziata ogni volta riducendo così il carico sul server e anche perché si occupa di caricare le classi di dominio (che sono inheritance di Domain) che mappano le tabelle del database in classi 
 
 - **Domain (abstract class)**: questa è la classe base di tutte le classi di Dominio si occupa di mappare le tabelle del database in classi più maneggevoli da usare su **PHP** utilizzando questa tipologia di mappatura dove bisogna specificare per ogni classe le connessioni con altre classi (implementando i metodi astratti **benlongsTo** e **hasMany**) quindi quando si esegue una query con la funzione findAll (es: SELECT * FROM Orders) vengono ritornate tutte le istanze della tabella Orders mappate sulla classe di Dominio Orders nel caso la tabella Orders ha una foreign key in una sua colonna questa proprietà viene mappata nella classe non come valore dell'id della foreign key ma con una istanza della classe di dominio alla quale quella tabella e mappata (es: Orders FK PersonID -> (PersonID,'Persons') viene ritornata una istanza della classe Persons) con questa tipologia di implementazione e necessario evitare tabelle cicliche *ed e per questo motivo che nella relazione tasklist e stata introdotta una rindondanza.*

![enter image description here](https://github.com/darkimage/Universita-Basididati/raw/master/documents/images/domain_example.png)

La relazione completa del progetto e disponibile [qui](https://github.com/darkimage/Universita-Basididati/raw/master/documents/basi_di_dati.pdf).

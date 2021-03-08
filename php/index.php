<?php
include("config.php");
include("header.php");
include("navbar.php");
include("popup.php");

if (isset($_GET['inscr'])) { //On affiche un message pour signifier la bonne inscription
  $msg = "Ton inscription a bien été prise en compte, il te reste qu'à te connecter avec le mot de passe envoyé par mail, pense à vérifier dans tes spams.";
}
if (isset($_GET['co'])) { //On affiche un message pour signifier la bonne connexion
  $msg = "Tu es bien connecté.";
}
if (isset($_GET['co2'])) { //On affiche un message pour signifier la bonne connexion et modification du mot de passe
  $msg = "Tu es bien connecté et ton mot de passe a été modifié.";

}
if (isset($_GET['deco'])) { //On affiche un message pour signifier la bonne déconnexion
  $msg = "Tu a bien été déconnecté.";
}

if (isset($_GET['change'])) { //On affiche un message pour signifier le bon changement de mot de passe
  $msg = "Ton nouveau mot de passe vous a été envoyé par email.";
}

if (isset($_GET['maj_admin'])) { //On affiche un message pour signifier la bonne mise à jour par un admin
  $msg = "Les données de l'utilisateur ont été mises à jour.";
}
if (!empty($msg)) {
  popup($msg);
}
 ?>

<div id="banner">
  <div class="container">
    <div class="title">
      <div id="logojiro"><img src="images/kiro.svg" width="500px"></div>
      <span class="byline">Concours Inter-écoles de Recherche Opérationnelle</span>
    </div>
  </div>
</div>
<div class="whitespace">
  <div id="extra" class="container">
    <div class="title" id="concours">
      <h2 style="font-size: 2.7em;">Un concours de recherche opérationnelle pour les étudiants</h2>
      <span class="byline">À l'École des Ponts ParisTech</span>
    </div>
    <p> Participez à la troisième édition du <strong>KIRO</strong>, un concours organisé par le <em>Club
        Informatique</em> de l'École des Ponts, en partenariat avec le <a href="https://www.renault.com" target="_blank">GROUPE RENAULT</a> et le laboratoire du <a href="https://cermics.enpc.fr" target="_blank">CERMICS</a>. Il s'agit d'un concours ouvert aux étudiants intéressés par la
      résolution effective d'un problème de recherche opérationnelle. </p>
    <p> En quelques mots, le KIRO c'est : </p>
    <ul class="list">
      <li>Des équipes de 3</li>
      <li>6h d'épreuve</li>
      <li>L'occasion de réfléchir à une véritable problématique d'entreprise</li>
      <li>L'opportunité de gagner un prix d'une valeur de 2 000 &euro;</li>
    </ul>
  </div>
</div>
<div id="featured">
  <div class="container">
    <div class="title box">
      <h2 style="font-size: 2.7em;">Une troisième édition</h2>
      <span class="byline">Sponsorisée par Renault et le laboratoire du CERMICS</span>
    </div>
    <div class="row h-100">
      <div class="col-sm-6">
        <div class="box box_bg" style="height: 100%"> <a href="https://cermics.enpc.fr"><img src="images/cermics.png" height="80" style="margin-top: -30px;"></a>
          <p style="margin-top: 15px;">Le <strong>CERMICS</strong> (Centre d'Enseignement et de Recherche
            en Mathématiques et Calcul Scientifique) est le laboratoire de recherche en mathématiques
            appliquées de l'École des Ponts ParisTech. Ses principaux domaines de recherche sont les
            Probabilités Appliquées, La Modélisation, l'Analyse et la Simulation et L'Optimisation des
            Systèmes.</p>
        </div>
      </div>
      <div class="col-sm-6">
        <div class="box box_bg" style="height: 100%"> <a href="https://www.renault.com"><img src="images/renault.png" width="250px"></a>
          <p style="margin-top: 15px;"> Constructeur automobile depuis 1898, le <strong>Groupe
              Renault</strong> est présent dans 134 pays et a vendu près de 3,9 millions de véhicules
            en 2018. Pour répondre aux défis technologiques du véhicule autonome, connecté et électrique
            et poursuivre sa stratégie de croissance rentable, le Groupe mise sur son développement à
            l’international, la complémentarité de ses cinq marques et son alliance avec Nissan et
            Mitsubishi Motors. </p>
        </div>
      </div>
    </div>
  </div>


</div>

<div class=whitespace>

  <div id="page" class="container">
    <div class="title" style="margin-bottom: 1em" id="old">
      <h2 style="font-size: 2.7em">Les anciens sujets</h2>
      <span class="byline">Liste des sujets des éditions précédentes</span>
    </div>

    <ul class="list2">
      <li> <a href="sujets/sujet1.pdf">Session 2017-2018, en partenariat avec Air France</a> et les <a href="sujets/sujet1.zip">instances</a></li>
      <li> <a href="sujets/sujet2.pdf">Session 2018-2019, en partenariat avec LocalSolver</a> et les <a href="sujets/sujet2.zip">instances</a></li>
    </ul>
  </div>
</div>


<div class="whitespace">
  <div id="page" class="container" style="margin-bottom: -40vh;">
    <div class="title" style="margin-bottom: 1em" id="reglement">
      <h2 style="font-size: 2.7em">Le planning</h2>
      <span class="byline">Date limite des inscriptions</span>
    </div>
    <p>Les inscriptions se termineront le <strong>24 Novembre à midi</strong>. Aucune inscription ne sera
      acceptée passé ce delai.</p>
    <div class="title" style="margin-bottom: 1em; padding-top: 2em;">
      <span class="byline">La session</span>
    </div>
    <p>Cette deuxième session se déroulera au sein de l'École des Ponts ParisTech à Champs-Sur-Marne le
      <strong>jeudi 28 Novembre</strong>.
    </p>
    <div class="row h-100">
      <div class="col-sm-2">
        <div class="box"> <span><strong>14h</strong></span>
          <p> Accueil des participants. </p>
        </div>
      </div>
      <div class="col-sm-2">
        <div class="box"> <span><strong>14h30</strong></span>
          <p> Début de l'épreuve. </p>
        </div>
      </div>
      <div class="col-sm-3">
        <div class="box"> <span><strong>20h</strong></span>
          <p> Dernière demie-heure, l'accès au palmarès des scores est figé. </p>
        </div>
      </div>
      <div class="col-sm-3">
        <div class="box"> <span><strong>20h30</strong></span>
          <p> Fin de l'épreuve, l'envoi des scores est bloqué. </p>
        </div>
      </div>
      <div class="col-sm-2">
        <div class="box"> <span><strong>21h</strong></span>
          <p> Fin de l'annonce des résultats. </p>
        </div>
      </div>
    </div>
    <div class="title" style="margin-bottom: 1em; padding-top: 2em;">
      <span class="byline">Le règlement</span>
    </div>
    <div class="row h-100">
      <div class="col-sm-4">
        <div class="box nobox">
          <span><strong>Article 1</strong></span>
          <p> L'inscription au concours est gratuite <br> Aucun frais n'est pris en charge par le KI. </p>
        </div>
      </div>
      <div class="col-sm-4">
        <div class="box nobox">
          <span><strong>Article 2</strong></span>
          <p> Chaque participant doit avoir entre 17 et 25 ans au 31 décembre de l'année 2018 et doit être
            inscrit dans une école d'ingénieur agréée par la CTI au cours de l'année 2019. </p>
        </div>
      </div>
      <div class="col-sm-4">
        <div class="box nobox">
          <span><strong>Article 3</strong></span>
          <p> La participation se fait par équipes de trois candidats. La présence physique de tous les
            candidats à l'Ecole des Ponts, ou dans un centre partenaire, pendant le concours est
            obligatoire. </p>
        </div>
      </div>
    </div>
    <div class="row h-100" style="margin-top: 20px; margin-bottom: 20px;">
      <div class="col-sm-4">
        <div class="box nobox">
          <span><strong>Article 4</strong></span>
          <p> Les équipes sont évaluées par un algorithme associant automatiquement aux solutions
            proposées un score en suivant les règles spécifiées dans le sujet délivré en début
            d'épreuve. </p>
        </div>
      </div>
      <div class="col-sm-4">
        <div class="box nobox">
          <span><strong>Article 5</strong></span>
          <p> L'équipe gagnante est annoncée suivant les résultats automatiques, mais cette décision n'est
            validée qu'après vérification du code de l'équipe. </p>
        </div>
      </div>

      <div class="col-sm-4">
        <div class="box nobox">
          <span><strong>Article 6</strong></span>
          <p> En acceptant le règlement, les candidats déclarent accepter l'enregistrement de leur image
            dont la diffusion et l'exploitation pourront se faire par le biais de sites Internet,
            réseaux sociaux, presse, films et photothèque. </p>
        </div>
      </div>
    </div>
    <p> Chaque équipe devra fournir au cours de son inscription : </p>
    <ul class="list2">
      <li> Les noms complets de chaque candidat.
      <li> Une adresse électronique pour confirmer l'inscription.
    </ul>
    <br>
    <p> Ces données sont nécessaires à la bonne organisation du concours. Les participants peuvent exercer les
      droits prévus par la loi Informatique et Libertés du 6 janvier 1978 modifiée en écrivant à <a href="mailto:kiro@clubinfo.enpc.fr">kiro@clubinfo.enpc.fr</a>. </p>
    <p> Le jour de l'événement : </p>
    <ul class="list2">
      <li> Vous devez apporter votre ordinateur, c'est avec celui-ci que vous allez envoyer vos solutions.
      <li> Tous les langages de programmation et bibliothèques sont autorisés.
      <li> L'équipe gagnante devra impérativement fournir l'algorithme qui a permis d'établir cette solution,
        même si celle-ci est aléatoire.
    </ul>
    <br>
    <p> Le non-respect de l'une des conditions énoncées précédemment entrainera une disqualification automatique
      sans recours possible. Le KI se réserve le droit de disqualifier sans réserve et sans recours possible
      les équipes n'ayant pas des conduites respectueuses envers les organisateurs ou les autres candidats.
    </p>
    <div class="title" style="margin-bottom: 1em; padding-top: 2em;" id="participer">
      <span class="byline">Participez !</span>
    </div>
    <ul class="actions">
      <li><button class="button" onclick="self.location.href='index.html'">S'inscrire</a></li>
      <br>
    </ul>
    <div class="title" style="margin-bottom: 1em; padding-top: 2em;">
      <span class="byline" id="acces">Plan d'accès</span>
    </div>
    <p>Le concours se déroule dans les locaux de l'Ecole des Ponts.</p>

    <p><strong>Cité Descartes, 6-8 Avenue Blaise Pascal, 77420 Champs-sur-Marne<br>
        Bâtiment Coriolis, aile Fresnel, premier étage</strong></p>

    <p>
      <img src="images/rera.png" width="60px"> Station <strong>Noisy-Champs</strong>
    </p>
    <iframe class="embed-responsive-item" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2625.89880101832!2d2.585794315902489!3d48.84106897928592!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e60e2d7f85dc39%3A0x14058c059473d90b!2s%C3%89cole+nationale+des+ponts+et+chauss%C3%A9es!5e0!3m2!1sfr!2sfr!4v1524491067082" width="100%" height="450" frameborder="0" style="border:0" allowfullscreen=""></iframe>
  </div>
</div>

<?php
include("footer.php");
?>

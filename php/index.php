<?php
include("config.php");
include("header.php");
include("navbar.php");

if (isset($_GET['inscr'])) { //On affiche un message pour signifier la bonne inscription
  $msg = "Ton inscription a bien été prise en compte, il te reste qu'à te connecter avec le mot de passe envoyé par mail, pense à vérifier dans tes spams.";
}
if (isset($_GET['co'])) { //On affiche un message pour signifier la bonne connexion
  $msg = "Tu es bien connecté.";
}
if (isset($_GET['co2'])) { //On affiche un message pour signifier la bonne connexion et modification du mot de passe
  $msg = "Tu es bien connecté et ton mot de passe a été modifié.";
}

if (isset($_GET['ns'])) {
  $msg_error = "Tu ne peux pas accéder à cette page pour le moment.";
}

if (isset($_GET['not_connected'])) {
  $msg_error = "Tu dois être connecté pour accéder à cette partie.";
}

if (isset($_GET['deco'])) { //On affiche un message pour signifier la bonne déconnexion
  $msg = "Tu as bien été déconnecté.";
}

if (isset($_GET['inscriptions_closes'])) { //On affiche un message pour signifier la bonne déconnexion
  $msg_error = "Les inscriptions sont closes.";
}

if (isset($_GET['already_co'])) { //On affiche un message pour signifier la bonne déconnexion
  $msg_error = "Vous êtes déjà connecté.";
}

if (isset($_GET['change'])) { //On affiche un message pour signifier le bon changement de mot de passe
  $msg = "Ton nouveau mot de passe t'a été envoyé par email.";
}

if (isset($_GET['maj_admin'])) { //On affiche un message pour signifier la bonne mise à jour par un admin
  $msg = "Les données de l'utilisateur ont été mises à jour.";
}

if (!empty($msg)) {
  echo 
  '<div class="alert alert-warning  alert-dismissible fade show" role="alert" style="position:fixed; bottom:0; margin:1rem;">
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    '.$msg.'
  </div>';
}
if (!empty($msg_error)) {
  echo 
  '<div class="alert alert-danger alert-dismissible fade show" role="alert" style="position:fixed; bottom:0; margin:1rem;">
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    '.$msg_error.'
  </div>';
}
?>

    <!-- Masthead-->
    <header class="masthead">
        <div class="container">
            <img src="assets/img/logo_kiro.png" alt="" style="width: 40%;">
            <div class="masthead-text text-uppercase">Concours inter-école de recherche opérationnelle</div>
        </div>
    </header>
    <!-- Description du concours-->
    <section class="page-section" id="descriptif">
        <div class="container" style="padding-top: 1rem;">
            <div class="text-center">
                <h2 class="section-heading text-uppercase">UN CONCOURS DE RECHERCHE OPÉRATIONNELLE POUR LES ÉTUDIANTS</h2>
                <h3 class="section-subheading text-muted" style="font-size: 1,5rem;">À l'École des Ponts ParisTech</h3>
            </div>
            <div class="row text-justify">
                <p>Participez à la cinquième édition du KIRO, un concours organisé par le Club Informatique de l'École des Ponts, 
                    en partenariat avec le groupe AIR LIQUIDE et le laboratoire du CERMICS. Il s'agit d'un concours ouvert aux étudiants 
                    intéressés par la résolution effective d'un problème de recherche opérationnelle.</p>
                <p> En quelques mots, le KIRO c'est : </p>
                <ul style="padding-left: 3rem;">
                    <li>Des équipes de 3</li>
                    <li>6h d'épreuve</li>
                    <li>L'occasion de réfléchir à une véritable problématique d'entreprise</li>
                    <li>L'opportunité de gagner des prix d'un montant total de 3000 &euro;</li>
                </ul>
            </div>
        </div>
    </section>
    <!-- Partenaire-->
    <section class="page-section bg-light" id="partenaires">
        <div class="container">
            <div class="text-center" style=" border-radius: 10px; padding:1rem; background-color: rgba(73, 73, 73, 0.356);">
                <h2 class="text-white text-uppercase" style="font-size: 2.7em;">Une cinquième édition</h2>
                <span class="text-white" style="font-size: 1.7em;">Sponsorisée par AIR LIQUIDE et le laboratoire du CERMICS</span>
            </div>
                <div class="row h-100">
                    <div class="col-lg-6" style="margin-top: 2rem;">
                        <div class="box">
                            <a href="https://cermics.enpc.fr">
                                <img src="assets/img/cermics.png" height="120px">
                            </a>
                            <p style="margin-top: 15px;">Le <strong>CERMICS</strong> (Centre d'Enseignement et de Recherche
                                en Mathématiques et Calcul Scientifique) est le laboratoire de recherche en mathématiques
                                appliquées de l'École des Ponts ParisTech. Ses principaux domaines de recherche sont les
                                Probabilités Appliquées, La Modélisation, l'Analyse et la Simulation et L'Optimisation des
                                Systèmes.</p>
                        </div>
                    </div>
                    <div class="col-lg-6" style="margin-top: 2rem;">
                        <div class="box">
                            <a href="https://www.airliquide.com/fr">
                                <img src="assets/img/air_liquide.png" height="90px">
                            </a>
                            <p style="margin-top: 15px;"> Créé en 1937, le <strong>Groupe SNCF</strong> est l’entreprise 
                                ferroviaire publique française. Leur réseau compte près de 29 000 km de lignes, 3000 gares ferroviaires, 
                                et gère chaque jour 15 000 départs de trains pour 10 millions de voyageurs et 250 000 tonnes de 
                                marchandises. En perpétuelle évolution, le groupe investit dans le développement et la modernisation 
                                du réseau ferré national, tout en s’adaptant aux défis environnementaux actuels. Il possède aussi 
                                plusieurs filiales à l’international, telles que Geodis, spécialisée dans le transport de 
                                marchandises, ou encore Keolis, leader mondial du métro automatique et du tramway. </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Annales-->
    <section class="page-section" id="annales">
        <div class="container">
            <div class="text-center">
                <h2 class="section-heading text-uppercase">Les anciens sujets</h2>
                <h3 class="section-subheading text-muted">Les sujets des années précédentes</h3>
            </div>
            <ul class="list-centered">
                <li> <a href="sujets/sujet1.pdf" target="_blank">Session 2017-2018, en partenariat avec Air France</a> et les <a href="sujets/sujet1.zip" target="_blank">instances</a></li>
                <li> <a href="sujets/sujet2.pdf" target="_blank">Session 2018-2019, en partenariat avec LocalSolver</a> et les <a href="sujets/sujet2.zip" target="_blank">instances</a></li>
                <li> <a href="sujets/sujet3.pdf" target="_blank">Session 2019-2020, en partenariat avec Renault</a> et les <a href="sujets/sujet3.zip" target="_blank">instances</a></li>
		            <li> <a href="sujets/sujet4.pdf" target="_blank">Session 2020-2021, en partenariat avec la SNCF</a> et les <a href="sujets/sujet4.zip" target="_blank">instances</a></li>
            </ul>
        </div>
    </section>
    <!-- Le Planning-->
    <section class="page-section" id="planning" style="border-top: 1px solid rgba(0, 0, 0, 0.15);">
        <div class="container">
            <div class="text-center">
                <h2 class="section-heading text-uppercase">Le Planning</h2>
                <h3 class="section-subheading-section text-muted">Date limite des inscriptions</h3>
                <p>Les inscriptions se termineront le 23 novembre à 23h59. Aucune inscription ne sera acceptée passé ce delai.</p>
                <h3 class="section-subheading-section text-muted">La session</h3>
                <p>Cette cinquième session se déroulera au choix au sein de l'École des Ponts ParisTech à Champs-sur-Marne ou à distance sur Discord le jeudi 25 novembre 2021.</p>
                <div class="row h-100">
                    <div class="col-lg-2" style="margin-top: 1rem;">
                      <div class="box"> <span><strong>13h30</strong></span>
                        <p> Accueil des participants. </p>
                      </div>
                    </div>
                    <div class="col-lg-2" style="margin-top: 1rem;">
                      <div class="box"> <span><strong>14h</strong></span>
                        <p> Début de l'épreuve. </p>
                      </div>
                    </div>
                    <div class="col-lg-3" style="margin-top: 1rem;">
                      <div class="box"> <span><strong>19h30</strong></span>
                        <p> Dernière demie-heure, l'accès au palmarès des scores est figé. </p>
                      </div>
                    </div>
                    <div class="col-lg-2" style="margin-top: 1rem;">
                      <div class="box"> <span><strong>20h</strong></span>
                        <p> Fin de l'épreuve, l'envoi des scores est bloqué. </p>
                      </div>
                    </div>
                    <div class="col-lg-3" style="margin-top: 1rem;">
                      <div class="box"> <span><strong>21h</strong></span>
                        <p>Annonce des résultats et remise des prix à l'École des Ponts.</p>
                      </div>
                    </div>
                </div>
                <h3 class="section-subheading-section text-muted">Le règlement</h3>
                <div class="row h-100">
                    <div class="col-lg-4">
                      <div class="box-invisible">
                        <span><strong>Article 1</strong></span>
                        <p> L'inscription au concours est gratuite <br> Aucun frais n'est pris en charge par le KI. </p>
                      </div>
                    </div>
                    <div class="col-lg-4">
                      <div class="box-invisible">
                        <span><strong>Article 2</strong></span>
                        <p> Le concours est ouvert à tous mais les lots sont réservés aux équipes remplissant les conditions évoquées plus bas. </p>
                      </div>
                    </div>
                    <div class="col-lg-4">
                      <div class="box-invisible">
                        <span><strong>Article 3</strong></span>
                        <p>La participation se fait par équipes de trois candidats. Exceptionnellement, la présence physique des candidats n’est pas requise, et le concours pourra se faire en ligne sur Discord.</p>
                      </div>
                    </div>
                </div>
                <div class="row h-100" style="margin-top: 20px; margin-bottom: 20px;">
                    <div class="col-lg-4">
                      <div class="box-invisible">
                        <span><strong>Article 4</strong></span>
                        <p> Les équipes sont évaluées par un algorithme associant automatiquement aux solutions proposées un score en suivant les règles spécifiées dans le sujet délivré en début d'épreuve. </p>
                      </div>
                    </div>
                    <div class="col-lg-4">
                      <div class="box-invisible">
                        <span><strong>Article 5</strong></span>
                        <p> L'équipe gagnante est annoncée suivant les résultats automatiques, mais cette décision n'est
                          validée qu'après vérification du code de l'équipe. </p>
                      </div>
                    </div>
              
                    <div class="col-lg-4">
                      <div class="box-invisible">
                        <span><strong>Article 6</strong></span>
                        <p>En acceptant le règlement, les candidats déclarent accepter l'enregistrement de leur image dont la diffusion et l'exploitation pourront se faire par le biais de sites Internet, réseaux sociaux, presse, films et photothèque.</p>
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Inscription-->
    <section class="page-section" id="inscription" style="border-top: 1px solid rgba(0, 0, 0, 0.15);">
        <div class="container">
            <div class="text-center section">
                <p class="byline">Chaque équipe devra fournir au cours de son inscription :</p>
                <ul class="list-centered">
                    <li> Les noms complets de chaque candidat.
                    <li> Une adresse électronique pour confirmer l'inscription.
                </ul>
                <br>
                <p> 
                    Ces données sont nécessaires à la bonne organisation du concours. Les participants peuvent exercer les
                    droits prévus par la loi Informatique et Libertés du 6 janvier 1978 modifiée en écrivant à 
                    <a href="mailto:kiro.enpc@gmail.com">kiro.enpc@gmail.com</a>. 
                </p>
            </div>
            
            <div class="text-center section" style="border-top: 1px solid rgba(0, 0, 0, 0.15);">
                <p class="byline">Le jour de l'événement : </p>
                <ul class="list-centered">
                    <li> Vous devez apporter votre ordinateur, c'est avec celui-ci que vous allez envoyer vos solutions.
                    <li> Tous les langages de programmation et bibliothèques sont autorisés.
                    <li> Les instances et les solutions seront au format Json. Il est donc recommandé aux participants d'apprendre à utiliser les librairies correspondantes pour le language qu'ils souhaitent utiliser.
                    <li> L'équipe gagnante devra impérativement fournir l'algorithme qui a permis d'établir cette solution,
                        même si celle-ci est aléatoire.
                </ul>
                <br>
                <p> 
                    Le non-respect de l'une des conditions énoncées précédemment entrainera une disqualification automatique
                    sans recours possible. Le KI se réserve le droit de disqualifier sans réserve et sans recours possible
                    les équipes n'ayant pas des conduites respectueuses envers les organisateurs ou les autres candidats.
                </p>
            </div>
            
            <div class="text-center section" style="border-top: 1px solid rgba(0, 0, 0, 0.15);">
                <p class="byline">Les lots</p>

                <p>Voici les prix remis aux trois premières équipes étudiantes, à partager entre les membres du groupe:
                <ul class="list-centered">
                    <li>1er : 1500€</li>
                    <li>2ème : 1000 €</li>
                    <li>3ème : 500 €</li>
                </ul>
                En plus de ces prix, la 1ère équipe d’étudiants des ponts gagnera un Ipad par personne.
                </p>
                <button class="btn btn-info" onclick="self.location.href='inscription.php'">S'inscrire</button>
            </div>
            
            <div class="text-center section" style="border-top: 1px solid rgba(0, 0, 0, 0.15);">
                <p class="byline" id="acces">Plan d'accès</p>
                <p>Plusieurs options sont disponibles pour passer le KIRO, en <strong>présentiel ou distanciel</strong>.</br> L'édition présentielle se déroulera dans les locaux de l'Ecole des Ponts.</p>
                </br>
                <p><strong>Cité Descartes, 6-8 Avenue Blaise Pascal, 77420 Champs-sur-Marne<br>
                    Bâtiment Coriolis, aile Fresnel, premier étage <br>
                </strong></p>
                </br>
                <p>Pour ceux passant l’épreuve au format distanciel, le lien du discord vous sera envoyé après inscription.
                La remise des prix aura lieu sur place.
                </p>
            </div>
        </div>
    </section>
<?php
include("footer.php");
?>

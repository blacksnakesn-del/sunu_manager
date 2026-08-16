

I => Journal de Développement (DEVLOG)
**

    Nom & Prenom => Abdou Kebe
    Projet => sunu_manager(ERP PHP/POO)

**


II => [Vendredi - Phase 1] : Conception UML
**Heure de realisation** => 17h - 20h
**
    creation du structure du projet avec deux grand dossiers de base 
    -- Dossier ( Developement_Log & Documents);
    Le dossier "Developements" contient mon fichiers devlog.md c'est un document rédigé au fur et à mesure de votre avancement (pas au dernier moment) à la racine de votre projet.

    Le deuxieme dossier Documents contient des dossiers de modelisations 
        - diagramme de use case des 4 differents profil
        - diagramme de classe des entites utilisation

**

III => **Probleme / Difficulter**

**
    RAS
**



[Vendredi - Phase 1] : Conception & BDD Fallback
**
 -** Heure de réalisation** => Vendredi 21H - Samedi 12H
 - Ce qui a été fait : Creation de deux fichiers srcipts (schemas.sql & schemas_sqlite.sql)
 - **Difficultés / Obstacles** : 
    .. pour la realisation du schemas de base de donner j'ai rencontrer des problemes l'ors de la creation des tables et des cles migratoires ainsi que  dans l'ajout des contraintes check et autres! AI m'a beaucoup aider la dessus.

    ..  et pour le scripts du schemas sqlite j'ai pas compris grande chose la dessus j'ai travailler avec ai il ma expliquer puis proposer des exemples de pratique et j'ai eu une appercus un peu plus claire au depart mais apprentissages reste!!
    
**


[Vendredi - Phase 1] : Singleton Database & Fallback Automatique
 - Heure de réalisation => Samedi 12H - 15H
 - **Ce qui a été fait** : j'ai creer un dossier de depart appeler src puis inclure une autres dossier core
  et c'est dans cette dossier core que j'ai initialiser mon premiers fichiers de creation de connexion vers le data base 
   Si PostgreSQL n'est pas disponible, le bloc catch prend le relais et ouvre la base SQLite

 - **ifficultés / Obstacles** : 
  .. la gestion du redirection vers sqlite!



 [Samedi - Phase 2] :  Entités POO Pure


    **Heure de réalisation** : 16H - 19H 
- **Ce qui a été fait** : l'implémentation complète des classes Entités dans
                         src/Model/Entity/ correspondant au table du base de donnee. 

- **Difficultés / Obstacles** : j'ai rencontre pas mal obstacle l'ors de la creation des entites 
                            et de leur manipulations et surtouts un probleme de logique ou incoherences!



 [Samedi - Phase 2] :  Repositories & SQL Sécurisé
 
    **Heure de réalisation** : 20H - 00H 
   **Ce qui a été fait** : creer un dossier de depart Repository avec trois sous fichiers
   (Clients.Repository, Fournisseurs.Repository, Produits.Repositry) pour la gestion sécurisée des accès en base de données avec des requêtes préparées PDO. 

 **Difficultés / Obstacles** : toujours avec la gestion ou logique des fonctions metiers
 
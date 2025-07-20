# BakerySimulator
coming soon...


bin/run-serveur.sh
chmod +x bin/run-serveur.sh


doit implementer : 

Un bouton : suivis des amélioration 

-> redirige vers une page avec le nom de toutes les améliorations possédés.


Gestion de stock 

| Palier (\$) | Amélioration                     | Coût initial | Effet                                                        | Type              | Notes                                                   |
| ----------- | -------------------------------- | ------------ | ------------------------------------------------------------ | ----------------- | ------------------------------------------------------- |
| 0           | **Faire un pain**                | -            | +1 pain (modifié par bonus et multiplicateur)                | Base              | Déjà implémenté                                         |
| 0           | **Vendre les pains**             | -            | Gagne 1\$ par pain (modifiable plus tard)                    | Base              | Déjà implémenté                                         |
| 100         | **Auto-clicker niveau 1**        | 100 \$       | Produit 1 pain toutes les 5 secondes                         | Automatique       | Parfait pour donner du rythme sans clic                 |
| 150         | **+1 pain par clic**             | 150 \$       | +1 pain de base par clic (valeur cumulable)                  | Clic manuel       | Déjà implémenté avec scaling                            |
| 500         | **Multiplicateur clic +0.1**     | 500 \$       | Multiplie la production manuelle                             | Clic manuel       | Déjà implémenté avec scaling                            |
| 800         | **Augmenter le prix du pain**    | 800 \$       | Le pain vaut 2\$ au lieu de 1\$                              | Amélioration fixe | Peut évoluer à d’autres niveaux de prix (3\$, 5\$...)   |
| 1200        | **Auto-clicker niveau 2**        | 1200 \$      | Produit 2 pains toutes les 4 secondes                        | Automatique       | Cumulable ou amélioration du précédent ? À définir      |
| 1500        | **Bonus de vente**               | 1500 \$      | +10% de gains sur la vente totale                            | Vente             | Apporte une nouvelle logique                            |
| 2000        | **Auto-vendeur**                 | 2000 \$      | Vend automatiquement tous les pains toutes les 10 sec        | Automatique       | Très utile pour phase idle                              |
| 5000        | **Mini-usine**                   | 5000 \$      | Produit 10 pains par seconde                                 | Automatique       | Coût élevé mais accélération brutale                    |
| 7000        | **Marketing local**              | 7000 \$      | Bonus temporaire : +50% gains pendant 30 sec                 | Pouvoir           | Nécessite un cooldown                                   |
| 10 000      | **Système de Prestige**          | 10 000 \$    | Réinitialise le jeu, donne +5% gain permanent à chaque reset | Rejouabilité      | Ajoute de la durée de vie au jeu                        |
| 15 000      | **Augmenter prix du pain à 3\$** | 15 000 \$    | Pain vendu à 3\$                                             | Économie          | Peut être une nouvelle ligne ou amélioration en cascade |

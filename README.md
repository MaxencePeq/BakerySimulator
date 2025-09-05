# 🍞 Bakery Simulator

**Lien du jeu → [bakerysimulator.alwaysdata.net](https://bakerysimulator.alwaysdata.net)**

Bakery Simulator est un **jeu incremental / autoclicker** développé en **PHP natif**, avec un système de connexion et de sauvegarde persistante.  
Le joueur incarne un boulanger qui doit produire de la farine, fabriquer du pain et améliorer sa boulangerie pour générer toujours plus de revenus.  

---

## 🚀 Fonctionnalités principales

- **Système de compte utilisateur** (inscription, connexion, déconnexion).
- **Sauvegarde persistante** des parties (base de données MySQL).
- **Restauration automatique** de la partie au login.
- **Système de prestige / reset** : recommencez une partie depuis zéro pour progresser différemment.
- **Gestion des ressources** : farine, pain, argent.
- **Améliorations et autoclickers** pour accélérer la production.
- **Interface simple et responsive** en HTML/CSS.

---

## 🛠️ Stack technique

- **Langage backend** : PHP 8+
- **Base de données** : MySQL (AlwaysData hébergement)
- **Front-end** : HTML5, CSS3
- **Architecture** :
  - `public/` → pages accessibles (index, login, register, basepage, etc.)
  - `src/` → logique du jeu (gestion session, classes)
  - `vendor/` → dépendances Composer
  - `save.php` → sauvegarde en BDD
  - `reset.php` → prestige/reset de la partie

---

## 🎮 Comment jouer

1. Créez un compte ou connectez-vous.
2. Cliquez pour produire de la farine et du pain.
3. Dépensez vos gains pour acheter des améliorations et autoclickers.
4. Sauvegardez votre partie à tout moment.
5. Utilisez le système de prestige/reset pour recommencer avec de nouveaux avantages.

---

## 📌 Notes

- Le projet a été initialement développé avec **MAMP** (local), puis déployé sur **AlwaysData**.  
- La persistance des parties est gérée par une table `save_data` en JSON.
- Le système de login / logout / save a été fait par IA
- Les améliorations et mécaniques du jeu sont facilement extensibles.

---

## 👨‍💻 Auteur

Développé par **Pequeno Maxence** — Projet personnel démontrant des compétences en **PHP, MySQL, gestion de sessions et déploiement web**.


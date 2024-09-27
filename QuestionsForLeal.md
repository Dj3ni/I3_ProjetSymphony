# Questions for Léal

## 1. Entité à créer ou pas?
J'ai généré des occurrences pour mes évènements: elles sont créées dynamiquement via le service "EventOccurrenceGenerator" sous forme de liste.
Ensuite, via le EventSubscriptionForm, je génère une liste avec les différentes occurrences pour m'inscrire à plusieurs dates sur le même formulaire, Chaque date étant un clone de l'event principal avec des infos leur étant propre. De cette manière, ne sont stockées dans la DB que les clones pour lesquels il y a des inscriptions(utile si bcp d'occurrences)

==> est-ce une bonne approche? Ou vaut-il mieux créer une entité Occurrence avec une relation One to Many avec Event? 

## 2. Formulaire de Contact

J'ai créé un simple formulaire, non lié à une entité. Cependant, dans les tutos, ils le lient à une entité. Quand je demande à Chat GPT, il me dit que les 2 approches sont bonnes.

==> Quelle est la meilleure approche et pourquoi?

## 3. 
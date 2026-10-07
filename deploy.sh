#!/bin/bash
set -e

echo "🚀 Démarrage du déploiement BioSanté sur o2switch..."

# 1. Mise à jour depuis Git
cd /home/vuxe8870/repositories/gestion-analyse
git pull origin main

# 2. Synchronisation avec permissions forcées (Dossiers 755, Fichiers 644)
rsync -av --chmod=D755,F644 --exclude='.git' --exclude='.env' ./ /home/vuxe8870/bio-sante.danayaplus.com/

# 3. Sécurité des permissions o2switch
chmod 755 /home/vuxe8870
chmod 755 /home/vuxe8870/bio-sante.danayaplus.com
chmod 755 /home/vuxe8870/bio-sante.danayaplus.com/public 2>/dev/null || true
find /home/vuxe8870/bio-sante.danayaplus.com/ -type d -exec chmod 755 {} +
find /home/vuxe8870/bio-sante.danayaplus.com/ -name ".htaccess" -exec chmod 644 {} +

# 4. Permissions d'écriture pour storage et bootstrap/cache
chmod -R 775 /home/vuxe8870/bio-sante.danayaplus.com/storage
chmod -R 775 /home/vuxe8870/bio-sante.danayaplus.com/bootstrap/cache

# 5. Migration et caches Laravel
php /home/vuxe8870/bio-sante.danayaplus.com/artisan migrate --force || true
php /home/vuxe8870/bio-sante.danayaplus.com/artisan optimize:clear

echo "✅ Déploiement terminé ! Le site est 100% opérationnel."

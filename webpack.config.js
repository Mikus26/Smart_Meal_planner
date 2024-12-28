const Encore = require('@symfony/webpack-encore');
const path = require('path');

// Configure l'environnement d'exécution si ce n'est pas déjà fait
if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    // Répertoire où les fichiers compilés seront stockés
    .setOutputPath('public/build/')
    // Chemin public utilisé par le serveur web pour accéder au répertoire de sortie
    .setPublicPath('/build')
    // Ajoute une entrée principale (JavaScript et CSS)
    .addEntry('app', './assets/app.js')

    // Active le découpage des fichiers pour une meilleure optimisation
    .splitEntryChunks()

    // Nécessite un tag supplémentaire pour runtime.js
    .enableSingleRuntimeChunk()

    // Fonctionnalités supplémentaires
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())

    // Configure Babel pour les polyfills
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = '3.38';
    })

    // Active le support de Sass/SCSS
    .enableSassLoader((options) => {
        options.sassOptions = {
            quietDeps: true, // Réduit les avertissements provenant des dépendances
            includePaths: [path.resolve(__dirname, 'node_modules')]
        };
    })

    // Ajout d'alias pour simplifier les imports
    .addAliases({
        '~': path.resolve(__dirname, 'node_modules'),
        '@': path.resolve(__dirname, 'assets')
    })

    // Fournit automatiquement jQuery si nécessaire (décommenter si utilisé)
    //.autoProvidejQuery()
;

module.exports = Encore.getWebpackConfig();

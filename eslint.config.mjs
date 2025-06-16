import antfu from '@antfu/eslint-config'

export default antfu({
    ignores: ['**/vendor/', 'public/build/', 'meilisearch_data/', 'mysql_data/', 'node_modules/', 'storage/'],
    formatters: true,
    stylistic: {
        indent: 4,
    },
    yaml: {
        overrides: {
            'yaml/indent': ['error', 4, { indentBlockSequences: true, indicatorValueIndent: 2 }],
        },
    },
})

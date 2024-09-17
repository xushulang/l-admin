import antfu from '@antfu/eslint-config'

export default antfu({
    ignores: ['**/vendor/', 'public/build/'],
    formatters: true,
    stylistic: {
        indent: 4,
    },
    yaml: {
        overrides: {
            'yaml/indent': 2,
        },
    },
})

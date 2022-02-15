module.exports = {
  pathPrefix: "empresas",
  siteMetadata: {
    title: `Polen B2B`,
    siteUrl: `https://polen.me/empresas`,
  },
  plugins: [
    "gatsby-plugin-sass",
    "gatsby-plugin-react-helmet",
    'gatsby-plugin-resolve-src',
    {
      resolve: `gatsby-plugin-google-fonts`,
      options: {
        fonts: [`Inter`, `source sans pro\:400,600,700`],
        display: "swap",
      },
    },
  ],
};

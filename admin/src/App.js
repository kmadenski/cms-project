import React from 'react';
import { HydraAdmin, ResourceGuesser, dataProvider as baseDataProvider, fetchHydra as baseFetchHydra  } from '@api-platform/admin';
import parseHydraDocumentation from '@api-platform/api-doc-parser/lib/hydra/parseHydraDocumentation';
import { Redirect } from 'react-router-dom';

const entrypoint = process.env.REACT_APP_API_ENTRYPOINT;

const authProvider = () => ({
  login: ({ username, password }) =>  {
      const request = new Request(`${entrypoint}/authentication_token`, {
          method: 'POST',
          body: JSON.stringify({ username: 'admin@mas.pl', password: '123' }),
          headers: new Headers({ 'Content-Type': 'application/json' }),
      });
      return fetch(request)
          .then(response => {
              if (response.status < 200 || response.status >= 300) {
                  throw new Error(response.statusText);
              }
              return response.json();
          })
          .then(({ token }) => {
              localStorage.setItem('token', token);
          });
  }
});

const fetchHeaders = {'Authorization': `Bearer ${window.localStorage.getItem('token')}`};
const fetchHydra = (url, options = {}) => baseFetchHydra(url, {
    ...options,
    headers: new Headers(fetchHeaders),
});
const apiDocumentationParser = entrypoint => parseHydraDocumentation(entrypoint, { headers: new Headers(fetchHeaders) })
    .then(
        ({ api }) => ({api}),
        (result) => {
            switch (result.status) {
                case 401:
                    return Promise.resolve({
                        api: result.api,
                        customRoutes: [{
                            props: {
                                path: '/',
                                render: () => <Redirect to={`/login`}/>,
                            },
                        }],
                    });

                default:
                    return Promise.reject(result);
            }
        },
    );
const dataProvider = baseDataProvider(entrypoint, fetchHydra, apiDocumentationParser);


export default () => (
  <HydraAdmin
    apiDocumentationParser={ apiDocumentationParser }
    dataProvider={ dataProvider }
    authProvider={ authProvider }
    entrypoint={ entrypoint }
  >
    <ResourceGuesser name="people" />
  </HydraAdmin>
);

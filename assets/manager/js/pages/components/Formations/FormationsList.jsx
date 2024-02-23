import React from "react";
import PropTypes from 'prop-types';

import { Alert } from "@commonComponents/Elements/Alert";

import { NewsItem } from "@managerPages/News/NewsItem";

export function FormationsList ({ data, highlight, onDelete }) {
    return <div className="list">
        <div className="list-table">
            <div className="items items-news">
                <div className="item item-header">
                    <div className="item-content">
                        <div className="item-infos">
                            <div className="col-1">Actualité</div>
                            <div className="col-2">Visibilité</div>
                            <div className="col-3 actions" />
                        </div>
                    </div>
                </div>

                {data.length > 0
                    ? data.map((elem) => {
                        return <NewsItem key={elem.id} elem={elem} highlight={highlight} onDelete={onDelete} />;
                    })
                    : <Alert>Aucune donnée enregistrée.</Alert>
                }
            </div>
        </div>
    </div>
}

FormationsList.propTypes = {
    data: PropTypes.array.isRequired,
    onDelete: PropTypes.func.isRequired,
}

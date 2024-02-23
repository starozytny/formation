import React from "react";
import PropTypes from 'prop-types';

import { Alert } from "@commonComponents/Elements/Alert";

import { TaxsItem } from "@managerPages/Settings/Taxs/TaxsItem";

export function TaxsList ({ data, highlight, onDelete }) {
    return <div className="list">
        <div className="list-table">
            <div className="items items-taxs">
                <div className="item item-header">
                    <div className="item-content">
                        <div className="item-infos">
                            <div className="col-1">Code</div>
                            <div className="col-2">Taux</div>
                            <div className="col-3">Numéro comptable</div>
                            <div className="col-4 actions" />
                        </div>
                    </div>
                </div>

                {data.length > 0
                    ? data.map((elem) => {
                        return <TaxsItem key={elem.id} elem={elem} highlight={highlight} onDelete={onDelete} />;
                    })
                    : <Alert>Aucune donnée enregistrée.</Alert>
                }
            </div>
        </div>
    </div>
}

TaxsList.propTypes = {
    data: PropTypes.array.isRequired,
    onDelete: PropTypes.func.isRequired,
}

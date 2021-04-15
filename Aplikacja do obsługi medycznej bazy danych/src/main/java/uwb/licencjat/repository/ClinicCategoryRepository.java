package uwb.licencjat.repository;

import org.springframework.data.domain.Sort;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.data.jpa.repository.Query;
import org.springframework.data.repository.query.Param;
import uwb.licencjat.model.ClinicCategory;

import java.util.List;
import java.util.Optional;

public interface ClinicCategoryRepository extends JpaRepository<ClinicCategory, Long> {

    Optional<ClinicCategory> findByNameIgnoreCase(String name);

    @Query("SELECT c FROM ClinicCategory c WHERE UPPER(c.name) LIKE UPPER(CONCAT('%', :name, '%')) OR " +
            "UPPER(c.description) LIKE UPPER(CONCAT('%', :desc, '%'))")
    List<ClinicCategory> findAllByNameDescriptionContaining(
            @Param("name") String name, @Param("desc") String desc, Sort sort);

    boolean existsByNameIgnoreCase(String email);
}
